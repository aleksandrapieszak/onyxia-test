<?php

namespace App\EventListener;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Gedmo\Loggable\Entity\LogEntry;
use Gedmo\Loggable\LoggableListener;
use Gedmo\Translatable\TranslatableListener;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class ExtendedLoggableListener extends LoggableListener
{

    private TokenStorageInterface $tokenStorage;
    private TranslatableListener $translatableListener;
    private EntityManagerInterface $entityManager;
    private Connection $connection;

    public function __construct(TokenStorageInterface $tokenStorage,EntityManagerInterface $entityManager, Connection $connection, TranslatableListener $translatableListener
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
        $this->connection = $connection;
        $this->translatableListener=$translatableListener;


        parent::__construct();
    }

    public function getSubscribedEvents(): array
    {
        //zdarzenie przed zapisem do danych
        return [
            Events::prePersist,
        ];
    }

    /**
     * @throws Exception
     */
    public function prePersist(LifecycleEventArgs $args): void
    {


        if ($this->tokenStorage !== null &&
            $this->tokenStorage->getToken() !== null &&
            $this->tokenStorage->getToken()->getUser() !== null
        ) {

            $locale = $this->translatableListener->getListenerLocale();
            $entity = $args->getObject();

            if ($entity instanceof LogEntry) {

                //dump('ustawiamy username w tabeli logów');

                $entity->setUsername($this->tokenStorage->getToken()->getUser()->getUserIdentifier());
                dump($entity);


                if ($entity->getAction() ==='update' && $locale ==='pl_PL') {
                    $data = $entity->getData();
                    $entityClass = $entity->getObjectClass();
                    $id = $entity->getObjectId();

                    //zrob update w tabeli głównej
                    $this->updateInBaseTableById($id, $entityClass, $data);
                }
            }
        }
    }

    /**
     * @throws Exception
     */
    public function updateInBaseTableById(int $id, string $entityClass, array $data)
    {
        // Sprawdzanie, czy dane nie są puste
        if (empty($data)) {
            throw new \InvalidArgumentException('Dane do aktualizacji nie mogą być puste.');
        }

        // Sprawdzanie, czy nazwa klasy encji jest poprawna
        $parts = explode('\\', $entityClass);
        $lastWord = array_pop($parts);
        if (!$lastWord) {
            throw new \InvalidArgumentException('Nieprawidłowa nazwa klasy encji.');
        }
        $entityName = strtolower($lastWord);

        $conn = $this->entityManager->getConnection();

        // Przygotowanie części zapytania SQL dla wszystkich kolumn do aktualizacji
        $updates = [];
        $parameters = ['id' => $id];
        foreach ($data as $field => $value) {
            if (is_string($field)) {
                $updates[] = "\"$field\" = :$field";
                $parameters[$field] = $value;
            } else {
                throw new \InvalidArgumentException("Nieprawidłowa nazwa kolumny: $field");
            }
        }
        if (empty($updates)) {
            throw new \InvalidArgumentException('Brak poprawnych wartości do aktualizacji.');
        }
        $setClause = implode(', ', $updates); // tworzenie ciągu ustawień dla zapytania SQL

        // Budowanie zapytania SQL
        $sql = "UPDATE \"$entityName\" SET $setClause WHERE id = :id";
        try {
            $conn->executeStatement($sql, $parameters);
            dump('udało się');
        } catch (Exception $e) {
            throw new \Exception("Błąd aktualizacji danych w bazie: " . $e->getMessage(), 0, $e);
        }
    }
}