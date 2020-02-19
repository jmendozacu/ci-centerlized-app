# ci-centerlized-app


Installation:
1. Install composer.
2. download project.
3. run `composer install` in project directory.
4. Create new mysql db with name : `ion_auth`
5. import /ci-centerlized-app/blob/master/application/third_party/ion_auth/sql/ion_auth.sql file into database.


Default Username and password for login is:
Email : admin@admin.com
Password : password

Login URL:
http://localhost/codeigniter-3/index.php/auth

Create User URL:
http://localhost/codeigniter-3/index.php/auth/create_user



you can change registration urls from file : /codeigniter-3/application/config/config.php
like:
`$config['api_urls'] = [
    'registration' => [
        'drupal' => 'https://test-app.free.beeceptor.com/drupal/rest/V1/customers',
        'magento' => 'https://test-app.free.beeceptor.com/magento/rest/V1/customers',
    ]
];
`
