<?php
/**
 * Kodal Restrict Registration plugin for Craft CMS 3.x
 *
 * Kodal - Restrict Registration
 *
 * @link      https://github.com/seanjermey
 * @copyright Copyright (c) 2019 Sean Jermey
 */

namespace Kodal\RestrictRegistration;

use Craft;

use craft\elements\User;
use yii\base\ModelEvent;
use craft\fields\Email;
use Kodal\RestrictRegistration\models\Settings;
use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;
use yii\base\Event;

/**
 * Class Plugin
 *
 * @author    Sean Jermey
 * @package   Kodal/RestrictRegistration
 * @since     1.0.0
 *
 */
class Plugin extends \craft\base\Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var Plugin
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var bool
     */
    public $hasCpSettings = true;

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(User::class, User::EVENT_BEFORE_VALIDATE, function (ModelEvent $e) {

            $settings = $this->getSettings();
            $user     = $e->sender;
            $email    = $user->email;

            $allowedDomains = array_filter($settings->allowedDomains, function ($setting) use ($email) {
                return strpos($email, trim($setting['domain'])) !== false;
            });

            if (count($allowedDomains) === 0) {
                $user->addError('email', $settings->errorMessage);
            }
        });

        Craft::info(
            Craft::t(
                'restrict-registration',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * @return \craft\base\Model|Settings|null
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    protected function settingsHtml()
    : string
    {
        return Craft::$app->view->renderTemplate(
            'restrict-registration/settings',
            [
                'settings' => $this->getSettings(),
            ]
        );
    }
}
