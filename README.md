# Drupal 9 Test
This is a Drupal9 website sparingly built for the purposes of test.

## Table of Contents

[[_TOC_]]

## About Me

Passionate Software Engineer with more than 4.8 years of experience in application development using Drupal. Seeking to leverage concrete programming skills for delivering high quality products and services. Skilled in SQL, PHP, C++, jQuery, and Drupal.



### Tools & Prerequisites

The following tools are required for setting up the site. Ensure you are using the latest version or at least the minimum version mentioned below. Also, ensure that you have added [your SSH key in your GitLab account settings](https://docs.gitlab.com/ee/ssh/#adding-an-ssh-key-to-your-gitlab-account).

* [Composer](https://getcomposer.org/download/)
* [Docker](https://docs.docker.com/install/)
* [Lando](https://docs.lando.dev/basics/installation.html)
* [Git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)

*Additional Note*: Docker and Lando are highly recommended but optional. See the instructions on Alternative setup below for more details.

*Important Note*: Ensure you have sufficient RAM (ideally 16 GB, minimum 8 GB).

### Local environment setup

Once you have all the tools installed, proceed to clone the repository and run lando to start.

```bash
$ lando start
```

Once Lando has been setup successfully, it will display the links in the terminal. Next run the following to fetch all dependencies.

```bash
$ lando composer install
```

Once the application has successfully started, run the configuration import and database update commands.

```bash
# Import drupal configuration
$ lando drush cim
```

```bash
# Update database
$ lando drush updb
```

### Alternative setup

You are free to use an alternative local environment setup. Lando configuration is provided to help you complete the test faster but if you are more comfortable with an alternative such as DDEV, Docksal, or just plain-old LAMP setup, go ahead and set it up. Just know that we won't be able to support you if you run into trouble in those cases. Also, note that the commands mentioned above would have to be adjusted accordingly.

If you opt to pick another setup, you will also not get the database by default. Feel free to contact us to get a starter database that you can import to get started.