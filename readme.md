# LaraBBS

## User Module

1. **nmews/captcha**
2. **Resources**
3. **Implicit route binding**
4. **FormRequest**
5. **i18n**, by using **overtrue/laravel-lang**
6. Carbon use chinese language.
7. Form image upload.
8. Image cutter, by using **intervention/image**
9. **Policy**

## Topic List

1. Use migration to seed data
2. Add **summerblue/generator**, and generate code
3. Add **barryvdn/laravel-debugbar** to show debugbar
4. Add **hieu/active** to know that where element should add active class.
5. Eloquent Scope

## Topic CURD

1. Topic excerpt
2. Use **simditor** editor
3. Upload topic image
4. Use **mews/purifier** for prevent XSS attack
5. Use baidu api to translate topic slug **overtrue/pinyin**
6. **guzzlehttp/guzzle**
7. Move translate slug to queue job
8. Use **laravel/horizon** to monitor queues
9. Use **predis/predis** for queue driver

## Topic Replies

1. Trait method reload
2. Notification via database and email
3. `includeWhen` in blade
4. delete model event

## Role and Permissions

1. Use "spatie/laravel-permission" for permission management.
2. Use "summerblue/administrator" for manage admin.
3. `Gate::before()`
4. Use "viacreative/sudo-su" for quickly change user login
5. Config site information.

## Misc

1. Cache
2. Artisan command
3. Trait
4. Foreign reference
5. Redis hSet and hGet

## Stage

1. PostMan
2. **DingoApi**

## Phone Register

1. **overture/easysms**
2. Create service provider for easysms
3. Phone register sequence
4. Some response method
    1. `$this->response->array([])`
    2. `$this->response->array([])->setStatusCode(201)`
    3. `$this->response->errorInternal()`
    4. `$this->response->error('', 422)`
    5. `$this->response->created()`
    6. `$this->response->errorUnauthorzide()`
5. Add rate throttle, and config items for rate limit
6. **gregwar/captcha**
7. Verification attributes

## Thirdly Login and JWT

1. Wechat oauth2 login flow
2. *socialiteproviders/weixin*
3. JWT
4. *tymon/jwt-auth*

## User Data

1. *Fractal* transformer
2. Get data in api
3. Update data in api
4. Upload file in api

## Topic Data

1. Category index
2. Topic create, update, delete, index, show
3. *overtrue/laravel-query-logger* to see N+1 question

## Reply Data and Notification

1. Reply store, delete, index
2. Notification index, stats, markAsRead
3. Fractal multiple layer relationship and Eager loading
4. Patch have no idempotent, mark all notification as read use `patch` method.

## Permissions

1. `getAllPermissions` for `spatie/laravel-permission` package
2. Use `include` in transformers.

## Others

1. Add more api.

## Advance Training Wrapping Up

1. Dingo API
2. JWT for user authentication
3. Fractal
4. Send SMS
5. Create service provider
6. RESTful api design
7. Change database column
8. Create middleware
9. APi aate limites
10. Thirdly part login: social provider package
11. OAuth2
12. Passport
13. API test by Post Man.
