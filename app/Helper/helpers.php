<?php

function getProgressBarClass($score)
{
    if ($score >= 0.9) return 'bg-success';
    if ($score >= 0.5) return 'bg-warning';
    return 'bg-danger';
}

function getBadgeClassFcp($ms)
{
    if ($ms === null) return 'bg-secondary';
    if ($ms <= 2000) return 'bg-success';
    if ($ms <= 4000) return 'bg-warning';
    return 'bg-danger';
}

function getBadgeClassSpeedIndex($ms)
{
    if ($ms === null) return 'bg-secondary';
    if ($ms <= 3000) return 'bg-success';
    if ($ms <= 5000) return 'bg-warning';
    return 'bg-danger';
}

function getBadgeClassLcp($ms)
{
    if ($ms === null) return 'bg-secondary';
    if ($ms <= 2500) return 'bg-success';
    if ($ms <= 4000) return 'bg-warning';
    return 'bg-danger';
}

function getBadgeClassCls($cls)
{
    if ($cls === null) return 'bg-secondary';
    if ($cls <= 0.1) return 'bg-success';
    if ($cls <= 0.25) return 'bg-warning';
    return 'bg-danger';
}

function formatMs($ms)
{
    if ($ms === null) return 'N/A';
    if ($ms > 1000) return number_format($ms / 1000, 2) . ' s';
    return $ms . ' ms';
}