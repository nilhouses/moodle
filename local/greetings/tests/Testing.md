# [Moodle Testing](https://moodle.academy/mod/book/view.php?id=1162&chapterid=1149)

## 1. Setting up the environment

### 1.1. Installing composer
[Composer](https://getcomposer.org/download/) can be installed using Linux or Git Bash (if you're using Windows) following the link instructions or just executing `composer_installation.sh`.

```bash
    $sh composer_installation.sh
```

### 1.2. Installing PHPUnit to your Moodle folder
Open a terminal or command prompt window and type the following commands:

```bash
    cd /path-to-www/moodle
    composer install --dev
```

This will install all project dependencies from the `composer.lock` or `composer.json` file. Both these files are located in the root of your Moodle folder.

### 1.3. Defining phpunit values in the Moodle config.php file
The PHPUnit integration requires a dedicated database and dataroot. We will use the same Moodle database but allow new tables to be created with a different prefix for the table names.

Open the main Moodle `config.php`, located in the root of your Moodle folder, in your IDE and add the following lines:

```text
    $CFG->phpunit_prefix = 'phpu_';
    $CFG->phpunit_dataroot = '/home/example/phpu_moodledata';
```
Note:

- *$CFG->phpunit_prefix* defines the prefix that will be used for database tables created for PHPUnit tests.
- *$CFG->phpunit_dataroot* defines the folder for PHPUnit Moodle data.

Make sure that your web server has write access to this directory.
You can also create this folder manually and grant the required write permissions.

At this stage, everything is all wired up between Moodle and PHPUnit. The next steps will be to initialise the test environment and run PHPUnit tests.

### 2. Initialising the test environment

```bash
    cd /path-to-www/moodle
    php admin/tool/phpunit/cli/init.php
```

*CAREFUL!*, This command has to be repeated after:
- Any upgrade
- Plugin (un)installation
- Adding tests to a plugin you are developing


### 3. Running a PHPUnit test

Run the PHPUnit test.

```bash
    cd /path-to-www/moodle/
    vendor/bin/phpunit --filter 'tool_dataprivacy\\metadata_registry_test'
```

I've noticed windows isn't the best environment for this setup, i will try setting everything up in Linux in the next course.

*Expected output:*

```text
    Moodle 5.0+ (Build: 20250509), 69991972434efecec3ae7e5130d27c9b28d65e49
    Php: 8.3.20, mariadb: 10.11.11, OS: Linux 5.15.167.4-microsoft-standard-WSL2 x86_64
    PHPUnit 11.5.12 by Sebastian Bergmann and contributors.

    ...                                                                 3 / 3 (100%)

    Time: 00:02.046, Memory: 386.00 MB

    OK, but there were issues!
    Tests: 3, Assertions: 71, PHPUnit Deprecations: 3948.
```


### 4. Explore the PHPUnit test file

Open the test file in Visual Studio Code:

```bash
    code admin/tool/dataprivacy/tests/metadata_registry_test.php
```

The 3 tests we run were:

- test_get_registry_metadata_count().
- test_get_registry_metadata_null_provider_details().
- test_get_registry_metadata_provider_details().


## 2. Writing a unit test

### 1. Testing the lib.php file

We will only test the function `local_greetings_get_greeting()`, as this file is not really test

```bash
    cd /path-to-www/moodle/local/greetings
    mkdir tests
    cd tests
    code lib_test.php
```
Follow [these](https://moodle.academy/mod/book/view.php?id=1161&chapterid=1146) steps.

### 2. Executing the test

To execute everything we must execute:

```bash
    cd /path-to-www/moodle/local/greetings
    php admin/tool/phpunit/cli/init.php
    vendor/bin/phpunit --filter test_local_greetings_null_user
```

In windows some errors may appear, they can be ignored adding a testsuite in `phpunit.xml` after initialising the environment:

```text
    <testsuite name="greetings_testsuite">
        <directory suffix="_test.php">local/greetings/tests</directory>
    </testsuite>
```
And can erase warnings from terminal when executed using:

```bash
    vendor/bin/phpunit --testsuite greetings_testsuite
```