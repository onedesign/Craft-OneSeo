<?php
namespace Craft;

/**
 * Doblin Plugin
 */
class OdcSeoPlugin extends BasePlugin
{
    public function getName()
    {
        return Craft::t('ODC SEO');
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

        );
    }

    public function onAfterInstall()
    {
        craft()->odcSeo->run();
    }

    public function onBeforeUninstall()
    {
        craft()->odcSeo->destroyFieldGroup();
    }

    public function getSettingsHtml()
    {
       return craft()->templates->render('odcseo/settings', array(
           'settings' => $this->getSettings()
       ));
    }
}
