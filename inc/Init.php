<?php
/**
 * @package AK_Mapping_Service
 */

namespace Inc;

final class Init
{
    public static function get_services()
    {
        return [
            Pages\Admin::class,
            Base\Enqueue::class,
            Base\SettingsLinks::class,
            Api\MappingApi::class,
            Api\SearchApi::class,
            Components\BrickSummaryPresenter::class,
            Components\PathStepsPresenter::class,
            Components\MapSvgPresenter::class
        ];
    }
    public static function register_services()
    {
        foreach (self::get_services() as $class) {
            $service = self::instantiate($class);
            if (method_exists($service, 'register'))
            {
                $service->register();
            }
        }
    }
    private static function instantiate( $class )
    {
        return new $class();
    }
}