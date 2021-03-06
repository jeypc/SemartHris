<?php

namespace KejawenLab\Application\SemartHris\Repository;

use Doctrine\ORM\QueryBuilder;
use KejawenLab\Application\SemartHris\Component\Employee\Model\EmployeeInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Model\AllowanceInterface;
use KejawenLab\Application\SemartHris\Component\Salary\Repository\AllowanceRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class SalaryAllowanceRepository extends Repository implements AllowanceRepositoryInterface
{
    /**
     * @param EmployeeInterface  $employee
     * @param \DateTimeInterface $date
     *
     * @return AllowanceInterface[]
     */
    public function findByEmployeeAndDate(EmployeeInterface $employee, \DateTimeInterface $date): array
    {
        return $this->entityManager->getRepository($this->entityClass)->findBy([
            'employee' => $employee,
            'year' => $date->format('Y'),
            'month' => $date->format('n'),
        ]);
    }

    /**
     * @param Request     $request
     * @param string      $sortDirection
     * @param null|string $sortField
     * @param null|string $dqlFilter
     *
     * @return QueryBuilder
     */
    public function createListQueryBuilder(Request $request, string $sortDirection = 'ASC', ?string $sortField, ?string $dqlFilter): QueryBuilder
    {
        $now = new \DateTime();
        $state = $request->query->get('state', 'p');
        $year = $request->query->get('year', $now->format('Y'));
        $month = $request->query->get('month', $now->format('n'));

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('entity');
        $queryBuilder->from($this->entityClass, 'entity');
        $queryBuilder->innerJoin('entity.component', 'component');
        $queryBuilder->andWhere($queryBuilder->expr()->eq('component.fixed', $queryBuilder->expr()->literal(false)));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('component.state', $queryBuilder->expr()->literal($state)));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('entity.year', $queryBuilder->expr()->literal($year)));
        $queryBuilder->andWhere($queryBuilder->expr()->eq('entity.month', $queryBuilder->expr()->literal($month)));

        if (!empty($dqlFilter)) {
            $queryBuilder->andWhere($dqlFilter);
        }

        if (null !== $sortField) {
            $queryBuilder->orderBy('entity.'.$sortField, $sortDirection);
        }

        return $queryBuilder;
    }
}
