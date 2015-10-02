<?php
namespace Craft;

/**
 * Doblin Plugin
 */
class OdcSeoPlugin extends BasePlugin
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
          ),
          'defaultMetaDescription' => array(
              AttributeType::String, 'label' => 'Default Meta Description'
          ),
          'defaultMetaTitle' => array(
              AttributeType::String, 'label' => 'Default Meta Title'
          )
        );
    }

    public function onAfterInstall()
    {
        craft()->odcSeo->run();
    }

    public function getSettingsHtml()
    {
        return craft()->templates->render('odcseo/settings', array(
           'settings' => $this->getSettings()
        ));
    }
}
