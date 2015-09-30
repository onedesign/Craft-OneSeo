<?php
namespace Craft;

/**
 * ODC SEO Service
 */
class OdcSeoService extends BaseApplicationComponent
{
  protected $seoGroupId;

  public function run() {
    $this->_createSeoFields();
  }

  private function _createSeoFields() {
    OdcSeoPlugin::log('Creating the SEO field group.');

    $group = new FieldGroupModel();
    $group->name = 'ODC SEO';

    if (craft()->fields->saveGroup($group))
    {
      OdcSeoPlugin::log('SEO group created successfully.' . $group->id);
      $this->seoGroupId = $group->id;
      OdcSeoPlugin::log('seoGroupId set to ' . $this->seoGroupId);
    } else {
      OdcSeoPlugin::log('Could not save the Default field group.', LogLevel::Warning);
    }

    OdcSeoPlugin::log('Creating the SEO Title Field');

    $seoTitleField = new FieldModel();
    $seoTitleField->groupId        = $group->id;
    $seoTitleField->name           = 'SEO Title';
    $seoTitleField->handle         = 'odcSeoTitle';
    $seoTitleField->translatable   = true;
    $seoTitleField->type           = 'PlainText';
    $seoTitleField->settings       = array(
      'multiline' => '1',
      'placeholder' => 'Enter SEO title ...',
      'maxLength' => '',
      'initialRows' => ''
    );

    if (craft()->fields->saveField($seoTitleField))
    {
      OdcSeoPlugin::log('SEO Title field created successfully.');
    }
    else
    {
      OdcSeoPlugin::log('Could not save the SEO Title Field.', LogLevel::Warning);
    }

    OdcSeoPlugin::log('Creating the SEO meta Field');

    $seoMetaField = new FieldModel();
    $seoMetaField->groupId        = $group->id;
    $seoMetaField->name           = 'SEO Meta';
    $seoMetaField->handle         = 'odcSeoMeta';
    $seoMetaField->translatable   = true;
    $seoMetaField->type           = 'PlainText';
    $seoMetaField->settings       = array(
      'multiline' => '1',
      'placeholder' => 'Enter meta description ...',
      'maxLength' => '155',
      'initialRows' => '8'
    );

    if (craft()->fields->saveField($seoMetaField))
    {
      OdcSeoPlugin::log('SEO Meta field created successfully.');
    }
    else
    {
      OdcSeoPlugin::log('Could not save the SEO Meta Field.', LogLevel::Warning);
    }

    OdcSeoPlugin::log('Creating the SEO og:image Field');

    $seoImageField = new FieldModel();
    $seoImageField->groupId        = $group->id;
    $seoImageField->name           = 'SEO Share Image';
    $seoImageField->handle         = 'odcSeoImage';
    $seoImageField->translatable   = false;
    $seoImageField->type           = 'Assets';
    $seoImageField->settings       = array(
      'useSingleFolder' => '',
      'defaultUploadLocationSubpath' => '',
      'singleUploadLocationSubpath' => '',
      'restrictFiles' => '1',
      'allowedKinds' => ['image'],
      'limit' => '1',
      'sectionLabel' => 'Add Social Image'
    );

    if (craft()->fields->saveField($seoImageField))
    {
      OdcSeoPlugin::log('SEO og:image field created successfully.');
    }
    else
    {
      OdcSeoPlugin::log('Could not save the SEO og:image Field.', LogLevel::Warning);
    }

    // OdcSeoPlugin::log('Adding fields to sections with URLs');

    // // Append SEO fields to that layout
    // $seoFieldIds = array(
    //   $seoTitleField->id,
    //   $seoMetaField->id,
    //   $seoImageField->id
    // );

    // $seoFields = [];

    // foreach ($seoFieldIds as $fieldSortOrder => $fieldId) {
    //   $field = new FieldLayoutFieldModel();
    //   $field->fieldId   = $fieldId;
    //   $field->required  = 0;
    //   $field->sortOrder = ($fieldSortOrder+1);

    //   $seoFields[] = $field;
    // }

    // $allSections = craft()->sections->getAllSections();
    // foreach ($allSections as $section) {
    //   if ($section->hasUrls) {
    //     $sectionEntryTypes = $section->getEntryTypes();
    //     foreach ($sectionEntryTypes as $entryType) {
    //       $currentLayout = $entryType->getFieldLayout();
    //       $currentTabs = $currentLayout->getTabs();
    //       $currentFields = [];

    //       foreach ($currentTabs as $tab) {
    //         $currentFields[] = $tab->getFields();
    //       }

    //       $currentTabsCount = count($currentTabs);

    //       $newTab = new FieldLayoutTabModel();
    //       $newTab->name         = urldecode('ODC SEO');
    //       $newTab->sortOrder    = ($currentTabsCount+1);
    //       $newTab->setFields($seoFields);

    //       $currentTabs[] = $newTab;
    //       $currentLayout->setTabs($currentTabs);
    //       $currentLayout->setFields($currentFields);

    //       if (craft()->sections->saveEntryType($entryType))
    //       {
    //         OdcSeoPlugin::log($section->name . ' ' . $entryType . 'saved successfully');
    //       }
    //       else
    //       {
    //         OdcSeoPlugin::log('Could not save the '. $section->name . ' '. $entryType . ' type.', LogLevel::Warning); 
    //       }
    //     }
    //   }
    // }

  }

  private function _associateSeoFields() {

  }

  public function destroyFieldGroup() {
    // OdcSeoPlugin::log('SeoGroupID = '. $this->seoGroupId);
  }
}