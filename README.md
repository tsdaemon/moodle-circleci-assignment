This submission plugin allows to test submissions with complex end-to-end scenarios using continuous integration system 
[CircleCI](https://circleci.com/).

Read more about CircleCI workflow [here](https://circleci.com/blog/what-is-continuous-integration/) and how to prepare your first project [here](https://circleci.com/docs/2.0/getting-started/).

# Requirements

1. Publicly accesible AWS S3 bucket to store assignment files.
2. CircleCI account (free is fine).

# Getting started

## Installation

First you need to add this plugin to your Moodle installation. Clone this repository to `/mod/assign/submission/` folder:

```
git clone https://github.com/tsdaemon/moodle-circleci-assignment {your_moodle_folder}/mod/assign/submission/circleci
```

Next run Moodle as administrator. It will propose you to install added plugins. Install it and configure AWS parameters: key, secret, region, and bucket.

## Prepare your repository

To create CircleCI project you need [GitHub](https://github.com) or [BitBucket](https://bitbucket.org/) repository with test scenarious. Start from creating 
a configuration file `.circleci/config.yml` in your repository with a following content:

```
version: 2
jobs:
  build:
    docker:
      - image: circleci/python:3.6.1
    working_directory: ~/repo

    steps:
      - checkout

      - run:
          name: download zip and unpack
          command: |
            wget $FILE_URL -O $HOME/target.zip
            mkdir $HOME/target
            unzip $HOME/target.zip -d $HOME/target
```

This boilerplate config defines job `build` which should be exeuted within [Docker](https://www.docker.com/) image `circleci/python:3.6.1`. This job include two steps. The first step checkouts the latest version of your repository into the Docker machine. The second step downloads submission file and unzip it into directory `$HOME/target`. 

This is an example of CircleCI configuration. You can extend it with more steps following CircleCI [Configuration Reference](https://circleci.com/docs/2.0/configuration-reference/).

## CircleCI project

To start using CircleCI
