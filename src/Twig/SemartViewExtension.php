<?php

namespace KejawenLab\Application\SemartHris\Twig;

use KejawenLab\Application\SemartHris\Util\MonthUtil;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class SemartViewExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return array(
            new \Twig_SimpleFunction('semarthris_month_options', array($this, 'createMonthOptions')),
            new \Twig_SimpleFunction('semarthris_year_options', array($this, 'createYearOptions')),
        );
    }

    /**
     * @return string
     */
    public function createMonthOptions(): string
    {
        $options = '';
        foreach (MonthUtil::getMonths() as $key => $month) {
            $options .= sprintf('<option value="%d" %s>%s</option>', $key, date('n') == $key ? 'selected="selected"' : '', $month);
        }

        return $options;
    }

    /**
     * @param int $limit
     *
     * @return string
     */
    public function createYearOptions($limit = 7): string
    {
        $yearNow = date('Y');

        $options = '';
        for ($i = 0; $i <= $limit; ++$i) {
            $year = $yearNow - $i;
            $options .= sprintf('<option value="%d" %s>%s</option>', $year, $year == $yearNow ? 'selected="selected"' : '', $year);
        }

        return $options;
    }
}
