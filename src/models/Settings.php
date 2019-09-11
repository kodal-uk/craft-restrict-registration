<?php
/**
 * restrict registration plugin for Craft CMS 3.x
 *
 * Kodal restrict registration
 *
 * @link      https://github.com/seanjermey
 * @copyright Copyright (c) 2019 Sean Jermey
 */

namespace Kodal\RestrictRegistration\models;

use Kodal\RestrictRegistration\Plugin;

use Craft;
use craft\base\Model;
use craft\validators\ArrayValidator;
use craft\validators\UniqueValidator;

/**
 * @author    Sean Jermey
 * @package   RestrictRegistration
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var array
     */
    public $allowedDomains = [];

    /**
     * @var string
     */
    public $errorMessage = 'Email domain not allowed';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['allowedDomains'], ArrayValidator::class],
            [['allowedDomains', 'errorMessage'], 'required'],
            ['errorMessage', 'default', 'value' => 'Email domain not allowed'],
        ];
    }
}
