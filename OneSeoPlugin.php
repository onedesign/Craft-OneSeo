<?php
namespace Craft;

/**
 * Doblin Plugin
 */
class OneSeoPlugin extends BasePlugin
{
    public function getName()
    {
        return Craft::t('SEO');
    }

    public function getVersion()
    {
        return '1.0.0';
    }

    public function getDeveloper()
    {
        return 'One Design Company';
    }

    public function getDeveloperUrl()
    {
        return 'http://www.onedesigncompany.com';
    }

    protected function defineSettings()
    {
        return array(
          'titleDividerCharacter' => array(
              AttributeType::String, 'label' => 'Meta Title Divider Character', 'default' => '–'
          )
        );
    }

    public function onAfterInstall()
    {
        craft()->oneSeo->run();
    }

    public function getSettingsHtml()
    {
        return craft()->templates->render('oneseo/settings', array(
           'settings' => $this->getSettings()
        ));
    }
}
