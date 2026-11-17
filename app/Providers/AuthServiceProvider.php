<?php

namespace App\Providers;

use App\Policies\AcademicQualificationPolicy;
use App\Policies\EmergencyContactPolicy;
use App\Policies\EnterprisePolicy;
use App\Policies\EntityPolicy;
use App\Policies\GalleryPolicy;
use App\Policies\HeadquarterPolicy;
use App\Policies\AllyPolicy;
use App\Policies\CampaignPolicy;
use App\Policies\LanguageProficienciePolicy;
use App\Policies\PersonPolicy;
use App\Policies\ProfessionalExperiencePolicy;
use App\Policies\ProfessionalSkillPolicy;
use App\Policies\ProfilePolicy;
use App\Policies\SkillPolicy;
use App\Policies\TemplatePolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('allUser', [UserPolicy::class, 'allUser']);
        Gate::define('findUser', [UserPolicy::class, 'findUser']);
        Gate::define('updateUser', [UserPolicy::class, 'updateUser']);
        Gate::define('toggleUser', [UserPolicy::class, 'toggleUser']);
        Gate::define('deleteUser', [UserPolicy::class, 'deleteUser']);
        Gate::define('restoreUser', [UserPolicy::class, 'restoreUser']);

        Gate::define('allPerson', [PersonPolicy::class, 'allPerson']);
        Gate::define('findPerson', [PersonPolicy::class, 'findPerson']);
        Gate::define('storePerson', [PersonPolicy::class, 'storePerson']);
        Gate::define('updatePerson', [PersonPolicy::class, 'updatePerson']);
        Gate::define('togglePerson', [PersonPolicy::class, 'togglePerson']);
        Gate::define('notifyPerson', [PersonPolicy::class, 'notifyPerson']);
        Gate::define('verifyPerson', [PersonPolicy::class, 'verifyPerson']);
        Gate::define('deletePerson', [PersonPolicy::class, 'deletePerson']);
        Gate::define('restorePerson', [PersonPolicy::class, 'restorePerson']);

        Gate::define('findProfile', [ProfilePolicy::class, 'findProfile']);
        Gate::define('storeProfile', [ProfilePolicy::class, 'storeProfile']);
        Gate::define('updateProfile', [ProfilePolicy::class, 'updateProfile']);

        Gate::define('allAcademicQualification', [AcademicQualificationPolicy::class, 'allAcademicQualification']);
        Gate::define('findAcademicQualification', [AcademicQualificationPolicy::class, 'findAcademicQualification']);
        Gate::define('storeAcademicQualification', [AcademicQualificationPolicy::class, 'storeAcademicQualification']);
        Gate::define('updateAcademicQualification', [AcademicQualificationPolicy::class, 'updateAcademicQualification']);
        Gate::define('deleteAcademicQualification', [AcademicQualificationPolicy::class, 'deleteAcademicQualification']);

        Gate::define('allLanguageProficiencie', [LanguageProficienciePolicy::class, 'allLanguageProficiencie']);
        Gate::define('findLanguageProficiencie', [LanguageProficienciePolicy::class, 'findLanguageProficiencie']);
        Gate::define('storeLanguageProficiencie', [LanguageProficienciePolicy::class, 'storeLanguageProficiencie']);
        Gate::define('updateLanguageProficiencie', [LanguageProficienciePolicy::class, 'updateLanguageProficiencie']);
        Gate::define('deleteLanguageProficiencie', [LanguageProficienciePolicy::class, 'deleteLanguageProficiencie']);

        Gate::define('allProfessionalSkill', [ProfessionalSkillPolicy::class, 'allProfessionalSkill']);
        Gate::define('findProfessionalSkill', [ProfessionalSkillPolicy::class, 'findProfessionalSkill']);
        Gate::define('storeProfessionalSkill', [ProfessionalSkillPolicy::class, 'storeProfessionalSkill']);
        Gate::define('updateProfessionalSkill', [ProfessionalSkillPolicy::class, 'updateProfessionalSkill']);
        Gate::define('deleteProfessionalSkill', [ProfessionalSkillPolicy::class, 'deleteProfessionalSkill']);

        Gate::define('allProfessionalExperience', [ProfessionalExperiencePolicy::class, 'allProfessionalExperience']);
        Gate::define('findProfessionalExperience', [ProfessionalExperiencePolicy::class, 'findProfessionalExperience']);
        Gate::define('storeProfessionalExperience', [ProfessionalExperiencePolicy::class, 'storeProfessionalExperience']);
        Gate::define('updateProfessionalExperience', [ProfessionalExperiencePolicy::class, 'updateProfessionalExperience']);
        Gate::define('deleteProfessionalExperience', [ProfessionalExperiencePolicy::class, 'deleteProfessionalExperience']);

        Gate::define('findEmergencyContact', [EmergencyContactPolicy::class, 'findEmergencyContact']);
        Gate::define('storeEmergencyContact', [EmergencyContactPolicy::class, 'storeEmergencyContact']);
        Gate::define('updateEmergencyContact', [EmergencyContactPolicy::class, 'updateEmergencyContact']);

        Gate::define('allEnterprise', [EnterprisePolicy::class, 'allEnterprise']);
        Gate::define('findEnterprise', [EnterprisePolicy::class, 'findEnterprise']);
        Gate::define('storeEnterprise', [EnterprisePolicy::class, 'storeEnterprise']);
        Gate::define('updateEnterprise', [EnterprisePolicy::class, 'updateEnterprise']);
        Gate::define('deleteEnterprise', [EnterprisePolicy::class, 'deleteEnterprise']);
        Gate::define('restoreEnterprise', [EnterprisePolicy::class, 'restoreEnterprise']);

        Gate::define('allEntity', [EntityPolicy::class, 'allEntity']);
        Gate::define('findEntity', [EntityPolicy::class, 'findEntity']);
        Gate::define('storeEntity', [EntityPolicy::class, 'storeEntity']);
        Gate::define('updateEntity', [EntityPolicy::class, 'updateEntity']);
        Gate::define('profileEntity', [EntityPolicy::class, 'profileEntity']);
        Gate::define('deleteEntity', [EntityPolicy::class, 'deleteEntity']);
        Gate::define('restoreEntity', [EntityPolicy::class, 'restoreEntity']);

        Gate::define('allGallery', [GalleryPolicy::class, 'allGallery']);
        Gate::define('findGallery', [GalleryPolicy::class, 'findGallery']);
        Gate::define('storeGallery', [GalleryPolicy::class, 'storeGallery']);
        Gate::define('deleteGallery', [GalleryPolicy::class, 'deleteGallery']);
        Gate::define('destroyGallery', [GalleryPolicy::class, 'destroyGallery']);
        Gate::define('restoreGallery', [GalleryPolicy::class, 'restoreGallery']);

        Gate::define('allTemplate', [TemplatePolicy::class, 'allTemplate']);
        Gate::define('findTemplate', [TemplatePolicy::class, 'findTemplate']);
        Gate::define('storeTemplate', [TemplatePolicy::class, 'storeTemplate']);
        Gate::define('updateTemplate', [TemplatePolicy::class, 'updateTemplate']);
        Gate::define('settingTemplate', [TemplatePolicy::class, 'settingTemplate']);
        Gate::define('previewTemplate', [TemplatePolicy::class, 'previewTemplate']);
        Gate::define('deleteTemplate', [TemplatePolicy::class, 'deleteTemplate']);
        Gate::define('restoreTemplate', [TemplatePolicy::class, 'restoreTemplate']);

        Gate::define('allHeadquarter', [HeadquarterPolicy::class, 'allHeadquarter']);
        Gate::define('findHeadquarter', [HeadquarterPolicy::class, 'findHeadquarter']);
        Gate::define('storeHeadquarter', [HeadquarterPolicy::class, 'storeHeadquarter']);
        Gate::define('updateHeadquarter', [HeadquarterPolicy::class, 'updateHeadquarter']);
        Gate::define('deleteHeadquarter', [HeadquarterPolicy::class, 'deleteHeadquarter']);
        Gate::define('restoreHeadquarter', [HeadquarterPolicy::class, 'restoreHeadquarter']);

        Gate::define('allAlly', [AllyPolicy::class, 'allAlly']);
        Gate::define('findAlly', [AllyPolicy::class, 'findAlly']);
        Gate::define('storeAlly', [AllyPolicy::class, 'storeAlly']);
        Gate::define('updateAlly', [AllyPolicy::class, 'updateAlly']);
        Gate::define('deleteAlly', [AllyPolicy::class, 'deleteAlly']);
        Gate::define('restoreAlly', [AllyPolicy::class, 'restoreAlly']);

        Gate::define('allCampaign', [CampaignPolicy::class, 'allCampaign']);
        Gate::define('findCampaign', [CampaignPolicy::class, 'findCampaign']);
        Gate::define('storeCampaign', [CampaignPolicy::class, 'storeCampaign']);
        Gate::define('updateCampaign', [CampaignPolicy::class, 'updateCampaign']);
        Gate::define('deleteCampaign', [CampaignPolicy::class, 'deleteCampaign']);
        Gate::define('restoreCampaign', [CampaignPolicy::class, 'restoreCampaign']);

        Gate::define('allSkill', [SkillPolicy::class, 'allSkill']);
        Gate::define('findSkill', [SkillPolicy::class, 'findSkill']);
        Gate::define('storeSkill', [SkillPolicy::class, 'storeSkill']);
        Gate::define('updateSkill', [SkillPolicy::class, 'updateSkill']);
        Gate::define('deleteSkill', [SkillPolicy::class, 'deleteSkill']);
        Gate::define('restoreSkill', [SkillPolicy::class, 'restoreSkill']);
    }
}
