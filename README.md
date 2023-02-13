# CONTENTS OF THIS FILE

  - INSTALLATION
  - MODULES
  - CONFIGURATION
  - PROJECT TASK

## INSTALLATION
The repository has Drupal 10 installation code with composer.In order to get a working codebase, you need to run composer install --no-dev from the top level of the repository. This will install Symfony and other packages required by Drupal in the vendor/ directory.

## MODULES
Custom modules should be placed inside modules/custom Folder.

## CONFIGURATION
In Drupal, configuration is the collection of admin settings that determine how the site functions, as opposed to the content of the site.The Task configurations needs to be placed in config folder.

##PROJECT TASK

Migrate the "Registration Type" module

## Task
* The data is currently in a custom table and should be replaced by a custom Drupal entity
* Existing fields should be defined in entity
* The entity should be fieldable
* There is no need for a canonical URL
* Make suggestion if existing data should be moved manually or via migration script
* Create a pull request once work is complete 

## Setup
* Basic DDEV config is provided. Can be build/tested with a clean Drupal install and an import of the synced config.
* Add code to `feature/migration-code` branch
* Post any questions you might have as a comment in this ticket

## D7 Data
[Module files: registration_type.zip](https://github.com/Mellenger-Interactive/Evaluation-Project/raw/main/data/registration_type.zip)
[SQL data: Database file](https://github.com/Mellenger-Interactive/Evaluation-Project/raw/main/data/drupal_vul_registration_type.zip)

