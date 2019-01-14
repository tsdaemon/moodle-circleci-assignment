This submission plugin allows to test submissions with complex end-to-end scenarios using continuous integration system 
[CircleCI](https://circleci.com/).

Read more about CircleCI workflow [here](https://circleci.com/blog/what-is-continuous-integration/) and how to prepare your first project [here](https://circleci.com/docs/2.0/getting-started/).

# Requirements

1. Publicly accesible [AWS S3](https://aws.amazon.com/s3) bucket to store assignment files.
2. CircleCI account (free is fine).

# Getting started

## Installation

First you need to add this plugin to your Moodle installation. Clone this repository to `/mod/assign/submission/` folder:

```
git clone https://github.com/tsdaemon/moodle-circleci-assignment {your_moodle_folder}/mod/assign/submission/circleci
```

Next run Moodle as administrator. It will propose you to install added plugins. Install it and configure AWS parameters: key, secret, region, and bucket.

## Prepare your repository

To create CircleCI project you need a [GitHub](https://github.com) or [BitBucket](https://bitbucket.org/) repository with test scenarious. Start from creating 
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

After creating this configuration, you should push it to your remote repository.

This is an example of CircleCI configuration. You can extend it with more steps following CircleCI [Configuration Reference](https://circleci.com/docs/2.0/configuration-reference/).

## CircleCI project

1. Go to [https://circleci.com/add-projects](https://circleci.com/add-projects). 
2. Select your repository.
3. Skip sample configuration and press "Start building".

First test job will fail because it runs without `$FILE_URL` variable. This variable is sent when CircleCI submission is uploaded to Moodle.

4. Go to project settings -> API permissions. Press "Create token". Now you have CircleCI token.

## CircleCI submission

You need to configure a following variables for your assingment to support CircleCI submission:

1. CircleCI token, which you have from the previous step.
2. CircleCI URL. It can be composed as `https://circleci.com/api/v1.1/project/<vcs-type>/<org>/<repo>/tree/<branch>`. Read more about the URL [here](https://circleci.com/docs/2.0/api-job-trigger/).
3. CircleCI job name from your configuration.

After this students can submit their solutions and get them tested with CircleCI.

# Usage

* Each CircleCI submission builds a corresponding job. 
* This build downloads a submission file *(only single file submission is allowed!)* from AWS S3 and run any test configation you want on this submission files. 
* A student will have a build URL in his submission, which he can follow and check his submission status.
