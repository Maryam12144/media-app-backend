<?php
namespace Modules\Core\Support\LaravelLogViewer;


class Level
{
    /**
     * @var array
     */
    private $levels_classes = [
        'debug' => 'blue',
        'info' => 'blue',
        'notice' => 'blue',
        'warning' => 'orange',
        'error' => 'red',
        'critical' => 'red',
        'alert' => 'red',
        'emergency' => 'red',
        'processed' => 'blue',
        'failed' => 'orange',
    ];
    /**
     * @var array
     */
    private $levels_imgs = [
        'debug' => 'info-circle',
        'info' => 'info-circle',
        'notice' => 'info-circle',
        'warning' => 'exclamation-triangle',
        'error' => 'exclamation-triangle',
        'critical' => 'exclamation-triangle',
        'alert' => 'exclamation-triangle',
        'emergency' => 'exclamation-triangle',
        'processed' => 'info-circle',
        'failed' => 'exclamation-triangle'
    ];
    /**
     * @return array
     */
    public function all()
    {
        return array_keys($this->levels_imgs);
    }
    /**
     * @param $level
     * @return string
     */
    public function img($level)
    {
        return $this->levels_imgs[$level];
    }
    /**
     * @param $level
     * @return string
     */
    public function cssClass($level)
    {
        return $this->levels_classes[$level];
    }
}