---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.

<!-- END_INFO -->

#Account


APIs for Account.
<!-- START_71670d7b53b4f84bb013c6623ccc3427 -->
## Get Orders
Get Orders.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_orders" 
```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_orders"
);


fetch(url, {
    method: "POST",
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_orders`


<!-- END_71670d7b53b4f84bb013c6623ccc3427 -->

#Auth


APIs for managing user authencatication related activities
<!-- START_b7802a3a2092f162a21dc668479801f4 -->
## Reset password email
Send a reset link to the given user.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/password/email" \
    -H "Content-Type: application/json" \
    -d '{"email":"abc@example.com"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/password/email"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "abc@example.com"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/password/email`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `email` | string |  required  | The email of the user.
    
<!-- END_b7802a3a2092f162a21dc668479801f4 -->

<!-- START_8ad860d24dc1cc6dac772d99135ad13e -->
## Reset password
Reset the given user&#039;s password.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/password/reset" \
    -H "Content-Type: application/json" \
    -d '{"email":"abc@example.com","password":"123456","password_confirmation":"123456","token":"saepe"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/password/reset"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "abc@example.com",
    "password": "123456",
    "password_confirmation": "123456",
    "token": "saepe"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/password/reset`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `email` | string |  required  | The email of the user.
        `password` | string |  required  | The new password of the user.
        `password_confirmation` | string |  required  | The password confirmation of the user, should be same as password.
        `token` | string |  required  | The token sent in reset password link.
    
<!-- END_8ad860d24dc1cc6dac772d99135ad13e -->

<!-- START_229cd1291dbaff350914ca413eb4c22f -->
## Login consumer
Authenticate a user by email.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/consumer/login" \
    -H "Content-Type: application/json" \
    -d '{"email":"abc@example.com","password":"123456"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/consumer/login"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "abc@example.com",
    "password": "123456"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/consumer/login`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `email` | string |  required  | The email of the user.
        `password` | string |  required  | The password of the user.
    
<!-- END_229cd1291dbaff350914ca413eb4c22f -->

<!-- START_cb709966fb726624806bb59ca10e42b4 -->
## Register consumer
Register a consumer by email.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/consumer/register" \
    -H "Content-Type: application/json" \
    -d '{"email":"abc@example.com","password":"123456","first_name":"Bill","last_name":"Gates","receive_offers":true,"date_of_birth":"1993\/06\/24","timezone":"Asia\/Kolkata","provider":"facebook","provider_id":"abcd","card_id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/consumer/register"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "abc@example.com",
    "password": "123456",
    "first_name": "Bill",
    "last_name": "Gates",
    "receive_offers": true,
    "date_of_birth": "1993\/06\/24",
    "timezone": "Asia\/Kolkata",
    "provider": "facebook",
    "provider_id": "abcd",
    "card_id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/consumer/register`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `email` | string |  required  | The email of the user.
        `password` | string |  required  | The password of the user, is required in case provider is as 'facebook' or 'google'.
        `first_name` | string |  required  | The first name of the user.
        `last_name` | string |  required  | The last name of the user.
        `receive_offers` | boolean |  required  | Whether user like to receive offers.
        `date_of_birth` | string |  required  | The password of the user.
        `timezone` | string |  required  | Whether user like to receive offers.
        `provider` | string |  required  | It can be 'email', facebook' or 'google'.
        `provider_id` | string |  optional  | It is a string that is used identify user on social tool, is required if provider is as 'google' or 'facebook'.
        `card_id` | integer |  optional  | It is a integer value.
    
<!-- END_cb709966fb726624806bb59ca10e42b4 -->

<!-- START_fc8828a9bbf218f26d5363f8b8970da8 -->
## Social login
Authenticate a user by social login.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/social/login/token" \
    -H "Content-Type: application/json" \
    -d '{"token":"enim","provider":"facebook","user_identifier":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/social/login/token"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "token": "enim",
    "provider": "facebook",
    "user_identifier": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/social/login/token`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `token` | string |  required  | The token of the user.
        `provider` | string |  required  | It should be 'facebook' or 'google'.
        `user_identifier` | integer |  required  | It should be any integer.
    
<!-- END_fc8828a9bbf218f26d5363f8b8970da8 -->

<!-- START_91ef5581d7d44352c56c9821d3568d4e -->
## Login staff user
Authenticate a user by email.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/staff/login" \
    -H "Content-Type: application/json" \
    -d '{"email":"abc@example.com","password":"123456"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/staff/login"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "abc@example.com",
    "password": "123456"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/staff/login`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `email` | string |  required  | The email of the user.
        `password` | string |  required  | The password of the user.
    
<!-- END_91ef5581d7d44352c56c9821d3568d4e -->

<!-- START_0ac2872cfd46400fb5e3717f081197cd -->
## Validate email
Validate email.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/validate_email" \
    -H "Content-Type: application/json" \
    -d '{"email":"abc@example.com","type":"Consumer"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/validate_email"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "abc@example.com",
    "type": "Consumer"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/validate_email`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `email` | string |  required  | The email of the user.
        `type` | string |  required  | The type of the user, it can be 'Consumer' or 'Staff'
    
<!-- END_0ac2872cfd46400fb5e3717f081197cd -->

<!-- START_1291979a34d85924dbc8886a69956d23 -->
## Logout
Logout authenticated user.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/consumer/logout" 
```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/consumer/logout"
);


fetch(url, {
    method: "POST",
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/consumer/logout`


<!-- END_1291979a34d85924dbc8886a69956d23 -->

<!-- START_e0e1299722121f85f2f3828e79b895c0 -->
## Change password
Change user&#039;s password.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/consumer/change_password" \
    -H "Content-Type: application/json" \
    -d '{"password":"123456"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/consumer/change_password"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "password": "123456"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/consumer/change_password`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `password` | string |  required  | The password of a user.
    
<!-- END_e0e1299722121f85f2f3828e79b895c0 -->

<!-- START_bca1940d7d3b6ca259e22594f67d8142 -->
## Validate password
Validate consumer password.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/consumer/validate_password" \
    -H "Content-Type: application/json" \
    -d '{"email":"abc@example.com","password":"password"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/consumer/validate_password"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "abc@example.com",
    "password": "password"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/consumer/validate_password`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `email` | string |  required  | The email of the user, is required if provider is other than email.
        `password` | string |  required  | The password of the user, is required if provider is email.
    
<!-- END_bca1940d7d3b6ca259e22594f67d8142 -->

<!-- START_9aa02dda3faada599b471ccada68827b -->
## Change staff password
Change staff user&#039;s password.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/staff/change_password" \
    -H "Content-Type: application/json" \
    -d '{"password":"123456"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/staff/change_password"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "password": "123456"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/staff/change_password`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `password` | string |  required  | The password of a user.
    
<!-- END_9aa02dda3faada599b471ccada68827b -->

<!-- START_63a12d3c93fe6b547d29a16b6eb56fe6 -->
## Logout staff user
Logout authenticated staff user.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/staff/logout" 
```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/staff/logout"
);


fetch(url, {
    method: "POST",
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/staff/logout`


<!-- END_63a12d3c93fe6b547d29a16b6eb56fe6 -->

#CTAs


APIs for CTAs.
<!-- START_a5f60678287aeed6d7f6c025500ad52d -->
## Get CTAs
Get all published CTAs of a club.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/ctas" \
    -H "Content-Type: application/json" \
    -d '{"club_id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/ctas"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "club_id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/ctas`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `club_id` | integer |  required  | An id of a club.
    
<!-- END_a5f60678287aeed6d7f6c025500ad52d -->

<!-- START_9dba0a7944acec75cac1e59338c84850 -->
## Get CTA details
Get CTA details.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/cta_details" \
    -H "Content-Type: application/json" \
    -d '{"id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/cta_details"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/cta_details`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id` | integer |  required  | An id of a CTA.
    
<!-- END_9dba0a7944acec75cac1e59338c84850 -->

#Category


APIs for Category.
<!-- START_c06ea763cef48ad139b90d82db90dc2a -->
## Get Categories
Get Categories.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_categories" \
    -H "Content-Type: application/json" \
    -d '{"club_id":1,"type":"'abc'"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_categories"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "club_id": 1,
    "type": "'abc'"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_categories`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `club_id` | integer |  required  | An id of a club.
        `type` | string |  required  | A type of category.
    
<!-- END_c06ea763cef48ad139b90d82db90dc2a -->

<!-- START_847c38ef8dc6809981d2e82c291ce59c -->
## Get categories based on seat
Find product categories based on seat

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_categories_based_on_seat" \
    -H "Content-Type: application/json" \
    -d '{"club_id":11,"type":"temporibus","block_id":19,"seat":15}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_categories_based_on_seat"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "club_id": 11,
    "type": "temporibus",
    "block_id": 19,
    "seat": 15
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_categories_based_on_seat`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `club_id` | integer |  required  | An id of a club.
        `type` | string |  optional  | required.
        `block_id` | integer |  required  | An id of a block.
        `seat` | integer |  optional  | required, number of a Seat. Example : A15 (combination of row and seat)
    
<!-- END_847c38ef8dc6809981d2e82c291ce59c -->

#Club


APIs for Club.
<!-- START_a451427fb82eecad8d49a24f9e2ea1ba -->
## Set club
Pick up the club.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/set_default_club" \
    -H "Content-Type: application/json" \
    -d '{"club_id":4}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/set_default_club"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "club_id": 4
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/set_default_club`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `club_id` | integer |  required  | The club id of the club.
    
<!-- END_a451427fb82eecad8d49a24f9e2ea1ba -->

<!-- START_ebc16ab1882d3929b81c7c2b456dfdd0 -->
## Get club details
Get club details.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_club_details" \
    -H "Content-Type: application/json" \
    -d '{"id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_club_details"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_club_details`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id` | integer |  required  | An id of the club.
    
<!-- END_ebc16ab1882d3929b81c7c2b456dfdd0 -->

#Club app settings


APIs for app settings.
<!-- START_2ae704cc6d155a615ce3d77b30fcd0e8 -->
## Get club app settings
Get all published club app settings.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/club_app_settings" 
```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/club_app_settings"
);


fetch(url, {
    method: "POST",
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/club_app_settings`


<!-- END_2ae704cc6d155a615ce3d77b30fcd0e8 -->

#Club category


APIs for Club category.
<!-- START_9b8bf535256af9ac8f501e522c44be18 -->
## Get club categories
Get all published club categories that having atleast 1 club.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/club_categories" 
```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/club_categories"
);


fetch(url, {
    method: "POST",
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/club_categories`


<!-- END_9b8bf535256af9ac8f501e522c44be18 -->

#Collection Points


APIs for Collection Points.
<!-- START_7b531b5884ad09ebfb3e34e303199ef7 -->
## Get Collection Points
Get all published collection points of a club.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_collection_points" \
    -H "Content-Type: application/json" \
    -d '{"club_id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_collection_points"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "club_id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_collection_points`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `club_id` | integer |  required  | An id of a club.
    
<!-- END_7b531b5884ad09ebfb3e34e303199ef7 -->

<!-- START_ad3b313e0cf799db315f4d9fa2c1563a -->
## Get Product And Loyalty Reward Transaction Collection Points Wise
Get Product And Loyalty Reward Transaction Collection Points Wise which are not collected

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_product_and_loyalty_reward_transactions_collection_point_wise" \
    -H "Content-Type: application/json" \
    -d '{"collection_point_id":1,"club_id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_product_and_loyalty_reward_transactions_collection_point_wise"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "collection_point_id": 1,
    "club_id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_product_and_loyalty_reward_transactions_collection_point_wise`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `collection_point_id` | integer |  required  | An id of a collection point.
        `club_id` | integer |  required  | An id of a club.
    
<!-- END_ad3b313e0cf799db315f4d9fa2c1563a -->

<!-- START_768bbbf4b4b995ffa6820852f45f5f87 -->
## Change Order Status
Change Order Status

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/change_order_status" \
    -H "Content-Type: application/json" \
    -d '{"transaction_id":"1","status":"Ready","type":"1"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/change_order_status"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "transaction_id": "1",
    "status": "Ready",
    "type": "1"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/change_order_status`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `transaction_id` | required |  optional  | An id of a transaction.
        `status` | required, |  optional  | status of a transaction.
        `type` | required, |  optional  | type of a transaction.
    
<!-- END_768bbbf4b4b995ffa6820852f45f5f87 -->

<!-- START_6b4cbe694e236a96fbd59dbe349db4c7 -->
## Scan Order
Scan Order

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/scan_order" \
    -H "Content-Type: application/json" \
    -d '{"type":"loyalty_reward or product","transaction_id":"1"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/scan_order"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "type": "loyalty_reward or product",
    "transaction_id": "1"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/scan_order`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `type` | required, |  optional  | type of record.
        `transaction_id` | required, |  optional  | id of a transaction.
    
<!-- END_6b4cbe694e236a96fbd59dbe349db4c7 -->

#Consumer


APIs for Consumer.
<!-- START_3d04158f6b83e3a56dad4ca7d1580049 -->
## Update profile
Update consumer profile details.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/consumer/update_profile" \
    -H "Content-Type: application/json" \
    -d '{"email":"abc@example.com","first_name":"Bill","last_name":"Gates","date_of_birth":"25\/12\/1992","receive_offers":true,"timezone":"Asia\/Kolkata"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/consumer/update_profile"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "abc@example.com",
    "first_name": "Bill",
    "last_name": "Gates",
    "date_of_birth": "25\/12\/1992",
    "receive_offers": true,
    "timezone": "Asia\/Kolkata"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/consumer/update_profile`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `email` | string |  required  | The email of the user.
        `first_name` | string |  required  | The first name of the user.
        `last_name` | string |  required  | The last name of the user.
        `date_of_birth` | string |  required  | A date of birth of the user.
        `receive_offers` | boolean |  required  | Whether user like to receive offers.
        `timezone` | string |  required  | Whether user like to receive offers.
    
<!-- END_3d04158f6b83e3a56dad4ca7d1580049 -->

<!-- START_e9fc4f2e1ff3d675e1bbef9d0a72a6de -->
## Delete account
Delete consumer account.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/consumer/delete_account" \
    -H "Content-Type: application/json" \
    -d '{"email":"abc@example.com","password":"password"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/consumer/delete_account"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "abc@example.com",
    "password": "password"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/consumer/delete_account`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `email` | string |  required  | The email of the user, is required if provider is other than email.
        `password` | string |  required  | The password of the user, is required if provider is email.
    
<!-- END_e9fc4f2e1ff3d675e1bbef9d0a72a6de -->

<!-- START_617fda17c79ff580ac54fb42d9a0d524 -->
## Get profile
Get consumer profile details.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/consumer/get_profile" \
    -H "Content-Type: application/json" \
    -d '{"id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/consumer/get_profile"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/consumer/get_profile`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id` | integer |  required  | An id of the user.
    
<!-- END_617fda17c79ff580ac54fb42d9a0d524 -->

<!-- START_1b372546071535d3b762035638780aa9 -->
## Update settings
Update consumer profile details.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/consumer/update_settings" \
    -H "Content-Type: application/json" \
    -d '{"settings":"{is_notification_enabled: true}"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/consumer/update_settings"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "settings": "{is_notification_enabled: true}"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/consumer/update_settings`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `settings` | json |  required  | The keys and values of a user settings.
    
<!-- END_1b372546071535d3b762035638780aa9 -->

#Events


APIs for events.
<!-- START_8de6cc92d4755fef6852e636629cfdab -->
## Save event notification

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/save_event_notification" \
    -H "Content-Type: application/json" \
    -d '{"event_id":1,"reason":"unavailable"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/save_event_notification"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "event_id": 1,
    "reason": "unavailable"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/save_event_notification`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `event_id` | integer |  required  | An id of a match.
        `reason` | enum |  required  | A reason of a notification.
    
<!-- END_8de6cc92d4755fef6852e636629cfdab -->

<!-- START_b040013af87a3c2e8b8fd03a6504709f -->
## Get events
Get all events of a club.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_events" \
    -H "Content-Type: application/json" \
    -d '{"club_id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_events"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "club_id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_events`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `club_id` | integer |  required  | An id of a club.
    
<!-- END_b040013af87a3c2e8b8fd03a6504709f -->

<!-- START_484f2b20c8817f5e6b5e2c5c43c03012 -->
## Prepare checkout
Prepare checkout for Event Purchase.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/prepare_checkout_for_event_purchase" \
    -H "Content-Type: application/json" \
    -d '{"event_id":1,"number_of_seats":1,"consumer_card_id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/prepare_checkout_for_event_purchase"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "event_id": 1,
    "number_of_seats": 1,
    "consumer_card_id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/prepare_checkout_for_event_purchase`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `event_id` | integer |  required  | The id of event.
        `number_of_seats` | integer |  required  | The Number of seat.
        `consumer_card_id` | integer |  required  | The id of card.
    
<!-- END_484f2b20c8817f5e6b5e2c5c43c03012 -->

<!-- START_58bb0803aaa0af8b359fd89c001cf48e -->
## Event payment
Check payment status and response of event.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/event_purchase_payment" \
    -H "Content-Type: application/json" \
    -d '{"event_transaction_id":1,"checkout_id":"1"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/event_purchase_payment"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "event_transaction_id": 1,
    "checkout_id": "1"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/event_purchase_payment`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `event_transaction_id` | integer |  required  | The id of event transaction.
        `checkout_id` | string |  required  | The checkout id of payment.
    
<!-- END_58bb0803aaa0af8b359fd89c001cf48e -->

<!-- START_6474b0812839d1433b7a419f86cf5165 -->
## Email event tickets in pdf
Email event tickets in pdf.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/email_event_tickets_in_pdf" \
    -H "Content-Type: application/json" \
    -d '{"transaction_id":1,"email":"abc@example.com"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/email_event_tickets_in_pdf"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "transaction_id": 1,
    "email": "abc@example.com"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/email_event_tickets_in_pdf`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `transaction_id` | integer |  required  | The id of ticket transaction.
        `email` | string |  required  | An email id of a user.
    
<!-- END_6474b0812839d1433b7a419f86cf5165 -->

#Hospitality Suite


APIs for Hospitality Suite.
<!-- START_468183526148a480cb109440bb4c6491 -->
## Get Hospitality Suites
Get all Hospitality Suites.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_hospitality_suites" \
    -H "Content-Type: application/json" \
    -d '{"club_id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_hospitality_suites"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "club_id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_hospitality_suites`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `club_id` | integer |  required  | An id of a club.
    
<!-- END_468183526148a480cb109440bb4c6491 -->

<!-- START_eb216b18538b0c0042b749bfa69f2b28 -->
## Prepare checkout
Prepare checkout for Hospitality Suite Purchase.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/prepare_checkout_for_hospitality_suite_purchase" \
    -H "Content-Type: application/json" \
    -d '{"hospitality_suit_dietary_options":"qui","number_of_seats":1,"match_id":1091,"consumer_card_id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/prepare_checkout_for_hospitality_suite_purchase"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "hospitality_suit_dietary_options": "qui",
    "number_of_seats": 1,
    "match_id": 1091,
    "consumer_card_id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/prepare_checkout_for_hospitality_suite_purchase`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `hospitality_suit_dietary_options` | required |  optional  | The json format.
        `number_of_seats` | integer |  required  | The Number of seat.
        `match_id` | integer |  required  | The Match id.
        `consumer_card_id` | integer |  required  | The id of card.
    
<!-- END_eb216b18538b0c0042b749bfa69f2b28 -->

<!-- START_642f1e2b63894027dadd8fa46227ab07 -->
## Hospitality Suite Payment
Check payment status and response of hospitality suite.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/hospitality_suite_purchase_payment" \
    -H "Content-Type: application/json" \
    -d '{"hospitality_suite_transaction_id":1,"checkout_id":"1"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/hospitality_suite_purchase_payment"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "hospitality_suite_transaction_id": 1,
    "checkout_id": "1"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/hospitality_suite_purchase_payment`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `hospitality_suite_transaction_id` | integer |  required  | The id of hospitality suite transaction.
        `checkout_id` | string |  required  | The checkout id of payment.
    
<!-- END_642f1e2b63894027dadd8fa46227ab07 -->

<!-- START_d7241886ddcac176c84913ccc71648bf -->
## Get hospitality upcoming match.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_upcoming_matches_for_hospitality" 
```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_upcoming_matches_for_hospitality"
);


fetch(url, {
    method: "POST",
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_upcoming_matches_for_hospitality`


<!-- END_d7241886ddcac176c84913ccc71648bf -->

<!-- START_f97aaf303d58eff53ba79becfe658456 -->
## Get hospitality suite detail.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_hospitality_suite_detail" 
```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_hospitality_suite_detail"
);


fetch(url, {
    method: "POST",
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_hospitality_suite_detail`


<!-- END_f97aaf303d58eff53ba79becfe658456 -->

<!-- START_aebae48d0f30b3cfd585538fb45fda17 -->
## Email hositality suite tickets in pdf
Email hositality suite tickets in pdf.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/email_hospitality_suite_tickets_in_pdf" \
    -H "Content-Type: application/json" \
    -d '{"hospitality_suite_transaction_id":1,"email":"abc@example.com"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/email_hospitality_suite_tickets_in_pdf"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "hospitality_suite_transaction_id": 1,
    "email": "abc@example.com"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/email_hospitality_suite_tickets_in_pdf`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `hospitality_suite_transaction_id` | integer |  required  | The id of ticket transaction.
        `email` | string |  required  | An email id of a user.
    
<!-- END_aebae48d0f30b3cfd585538fb45fda17 -->

<!-- START_0e420372011ad0742512b56568e3376a -->
## Save hospitality suite notification

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/save_hospitality_suite_notification" \
    -H "Content-Type: application/json" \
    -d '{"hospitality_suite_id":1,"reason":"unavailable"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/save_hospitality_suite_notification"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "hospitality_suite_id": 1,
    "reason": "unavailable"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/save_hospitality_suite_notification`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `hospitality_suite_id` | integer |  required  | An id of a match.
        `reason` | enum |  required  | A reason of a notification.
    
<!-- END_0e420372011ad0742512b56568e3376a -->

#Loyalty Reward


APIs for Loyalty Reward.
<!-- START_054b9979647da6b4fd6a8ee17ebc365e -->
## Get Loyalty reward with options.

Get Loyalty reward with options.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_loyalty_reward_products" \
    -H "Content-Type: application/json" \
    -d '{"club_id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_loyalty_reward_products"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "club_id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_loyalty_reward_products`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `club_id` | integer |  required  | An id of a Club.
    
<!-- END_054b9979647da6b4fd6a8ee17ebc365e -->

<!-- START_c890abed0270e09ec76a6e4b6a2ba67b -->
## Get purchase loyalty reward product.

Get purchase loyalty reward product.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/purchase_loyalty_reward_product" \
    -H "Content-Type: application/json" \
    -d '{"club_id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/purchase_loyalty_reward_product"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "club_id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/purchase_loyalty_reward_product`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `club_id` | integer |  required  | An id of a Club.
    
<!-- END_c890abed0270e09ec76a6e4b6a2ba67b -->

<!-- START_5bd41e29ffcd1be127a688f709d10ecb -->
## Get purchase loyalty reward history.

Get purchase loyalty reward history.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_loyalty_reward_history" 
```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_loyalty_reward_history"
);


fetch(url, {
    method: "POST",
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_loyalty_reward_history`


<!-- END_5bd41e29ffcd1be127a688f709d10ecb -->

<!-- START_4b3a4281101d05d099bb1fecec329644 -->
## Get loyalty rewards based on seat
Find loyalty rewards based on seat

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_loyalty_reward_based_on_seat" \
    -H "Content-Type: application/json" \
    -d '{"club_id":2,"block_id":12,"seat":8}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_loyalty_reward_based_on_seat"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "club_id": 2,
    "block_id": 12,
    "seat": 8
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_loyalty_reward_based_on_seat`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `club_id` | integer |  required  | An id of a club.
        `block_id` | integer |  required  | An id of a block.
        `seat` | integer |  optional  | required, number of a Seat. Example : A15 (combination of row and seat)
    
<!-- END_4b3a4281101d05d099bb1fecec329644 -->

#Match


APIs for Match.
<!-- START_dfd6f7033c023695daaa087d6ae2d221 -->
## Get fixtures list
Get fixtures list and results.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_fixtures_list" 
```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_fixtures_list"
);


fetch(url, {
    method: "POST",
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_fixtures_list`


<!-- END_dfd6f7033c023695daaa087d6ae2d221 -->

<!-- START_b3c8c83a8b561fa7ec7e5fcc9eb3643a -->
## Get match details
Get match details.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/match_details" \
    -H "Content-Type: application/json" \
    -d '{"id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/match_details"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/match_details`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id` | integer |  required  | An id of a match.
    
<!-- END_b3c8c83a8b561fa7ec7e5fcc9eb3643a -->

<!-- START_cb9b171c0b8ff1b0cb47764099357ffc -->
## Get match details of an in progress match
Get match details of an in progress match.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_in_progress_match_details" \
    -H "Content-Type: application/json" \
    -d '{"id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_in_progress_match_details"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_in_progress_match_details`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id` | integer |  required  | An id of a match.
    
<!-- END_cb9b171c0b8ff1b0cb47764099357ffc -->

<!-- START_8624873fbadc86d9efc84c4c48848829 -->
## Get close to real time events
Get close to real time events.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_close_to_real_time_events" \
    -H "Content-Type: application/json" \
    -d '{"id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_close_to_real_time_events"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_close_to_real_time_events`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id` | integer |  required  | An id of a match.
    
<!-- END_8624873fbadc86d9efc84c4c48848829 -->

<!-- START_d1b13f3d61187993956110ab6d2bbc61 -->
## Get an upcoming match list.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_upcoming_matches" \
    -H "Content-Type: application/json" \
    -d '{"id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_upcoming_matches"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_upcoming_matches`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id` | integer |  required  | An id of home team or away team.
    
<!-- END_d1b13f3d61187993956110ab6d2bbc61 -->

<!-- START_a4581f994eca8f84799a133ddf512b72 -->
## Get a finished match list.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_finished_matches" \
    -H "Content-Type: application/json" \
    -d '{"id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_finished_matches"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_finished_matches`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id` | integer |  required  | An id of home team or away team.
    
<!-- END_a4581f994eca8f84799a133ddf512b72 -->

#Match player


APIs for Match player.
<!-- START_42fd124307e68f0b41896b73fe961d45 -->
## Get match players
Get match players list with no of votes.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_match_players_with_votes" \
    -H "Content-Type: application/json" \
    -d '{"match_id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_match_players_with_votes"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "match_id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_match_players_with_votes`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `match_id` | integer |  required  | An id of a match.
    
<!-- END_42fd124307e68f0b41896b73fe961d45 -->

<!-- START_e8c4b2c710bf227e0d4a67e7b9670515 -->
## Vote match player
Vote match player and get match players with updated data.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/vote_match_player" \
    -H "Content-Type: application/json" \
    -d '{"match_id":1,"player_id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/vote_match_player"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "match_id": 1,
    "player_id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/vote_match_player`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `match_id` | integer |  required  | An id of a match.
        `player_id` | integer |  required  | An id of a player.
    
<!-- END_e8c4b2c710bf227e0d4a67e7b9670515 -->

#Membership package


APIs for Membership package.
<!-- START_c0a32990ca01c63e9da294f28b6d4181 -->
## Get membership packages
Get membership packages.

> Example request:

```bash
curl -X GET \
    -G "http://mukesh-fanslive.dev.aecortech.com/api/get_membership_packages" 
```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_membership_packages"
);


fetch(url, {
    method: "GET",
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "error": "Token not provided"
}
```

### HTTP Request
`GET api/get_membership_packages`


<!-- END_c0a32990ca01c63e9da294f28b6d4181 -->

<!-- START_8a609a139e3ccc6a561891df0f3794fb -->
## Prepare checkout
Prepare checkout for membership package purchase.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/prepare_checkout_for_membership_package_purchase" \
    -H "Content-Type: application/json" \
    -d '{"membership_package_id":1,"consumer_card_id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/prepare_checkout_for_membership_package_purchase"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "membership_package_id": 1,
    "consumer_card_id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/prepare_checkout_for_membership_package_purchase`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `membership_package_id` | integer |  required  | The id of membership package.
        `consumer_card_id` | integer |  required  | The id of card.
    
<!-- END_8a609a139e3ccc6a561891df0f3794fb -->

<!-- START_c93be0a49684c51b2d710734e89e6935 -->
## Membership package payment
Check payment status and response of membership package.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/membership_package_purchase_payment" \
    -H "Content-Type: application/json" \
    -d '{"consumer_membership_package_id":1,"checkout_id":"1"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/membership_package_purchase_payment"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "consumer_membership_package_id": 1,
    "checkout_id": "1"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/membership_package_purchase_payment`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `consumer_membership_package_id` | integer |  required  | The id of consumer membership package.
        `checkout_id` | string |  required  | The checkout id of payment.
    
<!-- END_c93be0a49684c51b2d710734e89e6935 -->

<!-- START_54668e5161cf795ef34ef86a214bda18 -->
## Get user payment account
Get user payment account.

> Example request:

```bash
curl -X GET \
    -G "http://mukesh-fanslive.dev.aecortech.com/api/get_user_payment_account" 
```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_user_payment_account"
);


fetch(url, {
    method: "GET",
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "error": "Token not provided"
}
```

### HTTP Request
`GET api/get_user_payment_account`


<!-- END_54668e5161cf795ef34ef86a214bda18 -->

<!-- START_365ecc8ac62d72731d2a407c3d04f5b5 -->
## Delete user payment account
Delete user payment account.

> Example request:

```bash
curl -X DELETE \
    "http://mukesh-fanslive.dev.aecortech.com/api/delete_user_payment_account" 
```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/delete_user_payment_account"
);


fetch(url, {
    method: "DELETE",
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/delete_user_payment_account`


<!-- END_365ecc8ac62d72731d2a407c3d04f5b5 -->

#My Club


APIs for Club Information Pages.
<!-- START_243cbc126c6034ecdb8cd544d6e45d43 -->
## Get club information pages
Get all published club information pages.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/club_information_pages" 
```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/club_information_pages"
);


fetch(url, {
    method: "POST",
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/club_information_pages`


<!-- END_243cbc126c6034ecdb8cd544d6e45d43 -->

#News


APIs for News.
<!-- START_aeceef8aaaac3f0954bf7253ecfdb38a -->
## Get news
Get all published news of a club.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/news" \
    -H "Content-Type: application/json" \
    -d '{"club_id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/news"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "club_id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/news`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `club_id` | integer |  required  | An id of a club.
    
<!-- END_aeceef8aaaac3f0954bf7253ecfdb38a -->

<!-- START_5a354879f7d6f55d267a9b7321f601c4 -->
## Get news details
Get news details.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/news_details" \
    -H "Content-Type: application/json" \
    -d '{"id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/news_details"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/news_details`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id` | integer |  required  | An id of a news.
    
<!-- END_5a354879f7d6f55d267a9b7321f601c4 -->

#Payment IQ


APIs for Payment IQ
<!-- START_6a685fafe98e0161f5b2fd2aac2fc112 -->
## Verify user
Verify user.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/paymentiq/verifyuser" \
    -H "Content-Type: application/json" \
    -d '{"sessionId":"Banana","userId":"123456"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/paymentiq/verifyuser"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "sessionId": "Banana",
    "userId": "123456"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/paymentiq/verifyuser`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `sessionId` | string |  required  | The sessionId.
        `userId` | string |  required  | The id of the user.
    
<!-- END_6a685fafe98e0161f5b2fd2aac2fc112 -->

<!-- START_6b2991da26d0159e4572e576b2e70553 -->
## Authorize
Authorize.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/paymentiq/authorize" \
    -H "Content-Type: application/json" \
    -d '{"sessionId":"Banana","userId":"123456"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/paymentiq/authorize"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "sessionId": "Banana",
    "userId": "123456"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/paymentiq/authorize`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `sessionId` | string |  required  | The sessionId.
        `userId` | string |  required  | The id of the user.
    
<!-- END_6b2991da26d0159e4572e576b2e70553 -->

<!-- START_cbeecdd0af40a9e008e2b273255bea97 -->
## Transfer
Transfer.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/paymentiq/transfer" \
    -H "Content-Type: application/json" \
    -d '{"sessionId":"Banana","userId":"123456"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/paymentiq/transfer"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "sessionId": "Banana",
    "userId": "123456"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/paymentiq/transfer`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `sessionId` | string |  required  | The sessionId.
        `userId` | string |  required  | The id of the user.
    
<!-- END_cbeecdd0af40a9e008e2b273255bea97 -->

<!-- START_10e0dc6cb84a6e670d2aeeef510f8fe9 -->
## Verify user
Verify user.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/paymentiq/cancel" \
    -H "Content-Type: application/json" \
    -d '{"sessionId":"Banana","userId":"123456"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/paymentiq/cancel"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "sessionId": "Banana",
    "userId": "123456"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/paymentiq/cancel`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `sessionId` | string |  required  | The sessionId.
        `userId` | string |  required  | The id of the user.
    
<!-- END_10e0dc6cb84a6e670d2aeeef510f8fe9 -->

#Payment method - Card


APIs for managing consumer cards.
<!-- START_1960551492841b4a289dc7a15f068c51 -->
## Get checkout id
Get checkout id for add card.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_checkout_id_for_add_card" 
```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_checkout_id_for_add_card"
);


fetch(url, {
    method: "POST",
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_checkout_id_for_add_card`


<!-- END_1960551492841b4a289dc7a15f068c51 -->

<!-- START_8f9030edfe15887e07acca0cfdef06fd -->
## Registration notification.

> Example request:

```bash
curl -X GET \
    -G "http://mukesh-fanslive.dev.aecortech.com/api/registration_notification" 
```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/registration_notification"
);


fetch(url, {
    method: "GET",
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "status": "success"
}
```

### HTTP Request
`GET api/registration_notification`


<!-- END_8f9030edfe15887e07acca0cfdef06fd -->

<!-- START_48c355ce1923a77e97a42a8247f17348 -->
## Add card in registration
Add card in registration.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/add_card_in_registration" \
    -H "Content-Type: application/json" \
    -d '{"checkout_id":"abc","card_type":"Mastercard","truncated_pan":"2138"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/add_card_in_registration"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "checkout_id": "abc",
    "card_type": "Mastercard",
    "truncated_pan": "2138"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/add_card_in_registration`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `checkout_id` | string |  required  | The checkout id of the registration.
        `card_type` | string |  required  | The card type of the card.
        `truncated_pan` | string |  required  | The truncated pan of the card.
    
<!-- END_48c355ce1923a77e97a42a8247f17348 -->

<!-- START_40ca23ad0cbe6306af2c512b198d4b97 -->
## List cards
List cards.

> Example request:

```bash
curl -X GET \
    -G "http://mukesh-fanslive.dev.aecortech.com/api/list_cards" 
```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/list_cards"
);


fetch(url, {
    method: "GET",
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "error": "Token not provided"
}
```

### HTTP Request
`GET api/list_cards`


<!-- END_40ca23ad0cbe6306af2c512b198d4b97 -->

<!-- START_5ac609488ef9002b049ffd244bbbc868 -->
## Add card
Add card.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/add_card" \
    -H "Content-Type: application/json" \
    -d '{"checkout_id":"abc","card_type":"Mastercard","truncated_pan":"2138","postcode":"123456"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/add_card"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "checkout_id": "abc",
    "card_type": "Mastercard",
    "truncated_pan": "2138",
    "postcode": "123456"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/add_card`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `checkout_id` | string |  required  | The checkout id of the registration.
        `card_type` | string |  required  | The card type of the card.
        `truncated_pan` | string |  required  | The truncated pan of the card.
        `postcode` | string |  required  | A postcode.
    
<!-- END_5ac609488ef9002b049ffd244bbbc868 -->

<!-- START_6accf7f9b794c1d9b4c666e3b933d85c -->
## Remove card
Remove card.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/remove_card" \
    -H "Content-Type: application/json" \
    -d '{"id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/remove_card"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/remove_card`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id` | integer |  required  | An id of a card.
    
<!-- END_6accf7f9b794c1d9b4c666e3b933d85c -->

#Polls


APIs for polls.
<!-- START_c910dbeb0d7a4446d17f71c415b165b1 -->
## Get polls
Get all published polls of a club.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/polls" \
    -H "Content-Type: application/json" \
    -d '{"club_id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/polls"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "club_id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/polls`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `club_id` | integer |  required  | An id of a club.
    
<!-- END_c910dbeb0d7a4446d17f71c415b165b1 -->

<!-- START_5c8eaf868bcd233ea8be124dfd3311aa -->
## Get poll details
Get poll details.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/poll_details" \
    -H "Content-Type: application/json" \
    -d '{"id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/poll_details"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/poll_details`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id` | integer |  required  | An id of a poll.
    
<!-- END_5c8eaf868bcd233ea8be124dfd3311aa -->

<!-- START_50ad03fbb5525bc559080da673d786c3 -->
## Save poll result
Save poll result.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/save_poll_result" \
    -H "Content-Type: application/json" \
    -d '{"poll_id":1,"option_id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/save_poll_result"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "poll_id": 1,
    "option_id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/save_poll_result`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `poll_id` | integer |  required  | An id of a poll.
        `option_id` | integer |  required  | An id of a poll option.
    
<!-- END_50ad03fbb5525bc559080da673d786c3 -->

#Pricing bands


APIs for Pricing bands.
<!-- START_020c076cdcd64f7c8bb310b82388f18c -->
## Get pricing bands
Get pricing bands.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_pricing_bands" 
```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_pricing_bands"
);


fetch(url, {
    method: "POST",
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_pricing_bands`


<!-- END_020c076cdcd64f7c8bb310b82388f18c -->

#Product


APIs for Product.
<!-- START_bcf30b225f284ca3028daade61e991f2 -->
## Get Category Products or Special Offer Products
Get Category Products or Special Offer Products.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_category_products" \
    -H "Content-Type: application/json" \
    -d '{"category_id":1,"related_to":"category or special_offer"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_category_products"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "category_id": 1,
    "related_to": "category or special_offer"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_category_products`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `category_id` | integer |  required  | An id of a category.
        `related_to` | string |  optional  | required.
    
<!-- END_bcf30b225f284ca3028daade61e991f2 -->

<!-- START_f4c1498b1164ec0c9aa4de312151608a -->
## Search product
Search product

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_search_products" \
    -H "Content-Type: application/json" \
    -d '{"category_type":"'merchandise'","search_param":"'abc'"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_search_products"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "category_type": "'merchandise'",
    "search_param": "'abc'"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_search_products`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `category_type` | string |  required  | A type of a category.
        `search_param` | string |  required  | search parameter.
    
<!-- END_f4c1498b1164ec0c9aa4de312151608a -->

<!-- START_287b5765153ee2414e898d7ab89267be -->
## Prepare checkout
Prepare checkout for product purchase.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/prepare_checkout_for_product_purchase" \
    -H "Content-Type: application/json" \
    -d '{"consumer_card_id":1,"products":"{\"consumer_card_id\":111,\"currency\":\"EUR\",\"type\"=\"food_and_drink\",\"selected_collection_time\":\"half_time\",\"products\":[{\"product_id\":1,\"quantity\":1,\"transaction_timestamp\":\"2020-08-26 14:00:00\",\"product_options\":[{\"id\":1}]},{\"product_id\":2,\"quantity\":2,\"per_quantity_price\":10,\"total_price\":20,\"transaction_timestamp\":\"2020-08-26 14:00:00\",\"product_options\":[{\"id\":2}]}]}"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/prepare_checkout_for_product_purchase"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "consumer_card_id": 1,
    "products": "{\"consumer_card_id\":111,\"currency\":\"EUR\",\"type\"=\"food_and_drink\",\"selected_collection_time\":\"half_time\",\"products\":[{\"product_id\":1,\"quantity\":1,\"transaction_timestamp\":\"2020-08-26 14:00:00\",\"product_options\":[{\"id\":1}]},{\"product_id\":2,\"quantity\":2,\"per_quantity_price\":10,\"total_price\":20,\"transaction_timestamp\":\"2020-08-26 14:00:00\",\"product_options\":[{\"id\":2}]}]}"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/prepare_checkout_for_product_purchase`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `consumer_card_id` | integer |  required  | The id of card.
        `products` | json |  required  | A products data.
    
<!-- END_287b5765153ee2414e898d7ab89267be -->

<!-- START_21afc0f40a7386bf60c24c803f7a79c5 -->
## Product payment
Check payment status and response of product.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/product_purchase_payment" \
    -H "Content-Type: application/json" \
    -d '{"product_transaction_id":1,"checkout_id":"1"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/product_purchase_payment"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "product_transaction_id": 1,
    "checkout_id": "1"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/product_purchase_payment`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `product_transaction_id` | integer |  required  | The id of product transaction.
        `checkout_id` | string |  required  | The checkout id of payment.
    
<!-- END_21afc0f40a7386bf60c24c803f7a79c5 -->

<!-- START_99aab1859c7397704c994d247c3ffe35 -->
## Get Orders
Get Orders.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_product_orders" \
    -H "Content-Type: application/json" \
    -d '{"type":"expedita"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_product_orders"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "type": "expedita"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_product_orders`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `type` | string |  required  | 
    
<!-- END_99aab1859c7397704c994d247c3ffe35 -->

<!-- START_916972c2c4b3404455ba882efa4c9ef5 -->
## Get Product Configurations
Get Product Configurations

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_product_configurations" \
    -H "Content-Type: application/json" \
    -d '{"club_id":"velit","type":"a"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_product_configurations"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "club_id": "velit",
    "type": "a"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_product_configurations`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `club_id` | string |  required  | 
        `type` | string |  required  | 
    
<!-- END_916972c2c4b3404455ba882efa4c9ef5 -->

#Project Configuration


APIs for project configurations.
<!-- START_4d6613db637483e5e3aebf46b1caf578 -->
## Get a project configurations.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_project_configurations" 
```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_project_configurations"
);


fetch(url, {
    method: "POST",
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_project_configurations`


<!-- END_4d6613db637483e5e3aebf46b1caf578 -->

<!-- START_58ddb182b988b6b70247ff068f35c353 -->
## Get Time Zone

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_time_zones" 
```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_time_zones"
);


fetch(url, {
    method: "POST",
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_time_zones`


<!-- END_58ddb182b988b6b70247ff068f35c353 -->

#Quiz


APIs for Quiz.
<!-- START_d27c1fc25289c1c4a1fc793399c3b081 -->
## Get Quizzes

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/quizzes" \
    -H "Content-Type: application/json" \
    -d '{"club_id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/quizzes"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "club_id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/quizzes`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `club_id` | integer |  required  | An id of a club.
    
<!-- END_d27c1fc25289c1c4a1fc793399c3b081 -->

<!-- START_ada1051df24adf063c83879c4c2bbab8 -->
## Submit Quiz

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/submit_quiz" \
    -H "Content-Type: application/json" \
    -d '{"quiz_id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/submit_quiz"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "quiz_id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/submit_quiz`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `quiz_id` | integer |  required  | An id of a quiz.
    
<!-- END_ada1051df24adf063c83879c4c2bbab8 -->

#Stadium


APIs for Stadium.
<!-- START_5b8aa3b411ebce5f76d08947ba96f290 -->
## Get directions to stadium
Get directions to stadium by club.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_directions_to_stadium" \
    -H "Content-Type: application/json" \
    -d '{"club_id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_directions_to_stadium"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "club_id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_directions_to_stadium`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `club_id` | integer |  required  | An id of a club.
    
<!-- END_5b8aa3b411ebce5f76d08947ba96f290 -->

<!-- START_4db994dedeaf1b5461aeb319852dcc73 -->
## Find my Seat
Find my seat by seat number

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/find_my_seat_to_stadium" \
    -H "Content-Type: application/json" \
    -d '{"block_id":6,"seat":4}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/find_my_seat_to_stadium"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "block_id": 6,
    "seat": 4
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/find_my_seat_to_stadium`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `block_id` | integer |  required  | An id of a block.
        `seat` | integer |  optional  | required, number of a Seat. Example : A15 (combination of row and seat)
    
<!-- END_4db994dedeaf1b5461aeb319852dcc73 -->

#Staff


APIs for Staff user.
<!-- START_81904271efeeb3bee0b65257e0b19f95 -->
## Get profile
Get staff profile details.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/staff/get_profile" \
    -H "Content-Type: application/json" \
    -d '{"id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/staff/get_profile"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/staff/get_profile`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id` | integer |  required  | An id of the user.
    
<!-- END_81904271efeeb3bee0b65257e0b19f95 -->

<!-- START_e838cf87263bc49185754325b04a70ed -->
## Update profile
Update staff profile details.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/staff/update_profile" \
    -H "Content-Type: application/json" \
    -d '{"email":"abc@example.com","first_name":"Bill","last_name":"Gates"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/staff/update_profile"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "abc@example.com",
    "first_name": "Bill",
    "last_name": "Gates"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/staff/update_profile`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `email` | string |  required  | The email of the user.
        `first_name` | string |  required  | The first name of the user.
        `last_name` | string |  required  | The last name of the user.
    
<!-- END_e838cf87263bc49185754325b04a70ed -->

#Standing


APIs for Standing.
<!-- START_56999d299faf8287b836c64377e4950a -->
## Get Standing
Get all standings.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/standings" \
    -H "Content-Type: application/json" \
    -d '{"competition_id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/standings"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "competition_id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/standings`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `competition_id` | integer |  required  | An id of a competition.
    
<!-- END_56999d299faf8287b836c64377e4950a -->

#Ticket


APIs for Ticket.
<!-- START_89e3eec9f518ab387f2a7d74b5c7864b -->
## Save ticket notification
Save ticket notification.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/save_ticket_notification" \
    -H "Content-Type: application/json" \
    -d '{"match_id":1,"reason":"unavailable"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/save_ticket_notification"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "match_id": 1,
    "reason": "unavailable"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/save_ticket_notification`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `match_id` | integer |  required  | An id of a match.
        `reason` | enum |  required  | A reason of a notification.
    
<!-- END_89e3eec9f518ab387f2a7d74b5c7864b -->

<!-- START_a8909584e26d359fc57eefd481f5929c -->
## Prepare checkout
Prepare checkout for ticket purchase.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/prepare_checkout_for_ticket_purchase" \
    -H "Content-Type: application/json" \
    -d '{"consumer_card_id":1,"tickets":"[{\"stadium_block_seat_id\":451,\"row\":\"Z\",\"seat\":\"1\",\"type\":\"seat\",\"stadium_block_name\":\"W1H\",\"block_id\":1,\"pricing_bands\":[{\"id\":1,\"price\":2.3,\"display_name\":\"Adult\",\"is_selected\":true}]},{\"stadium_block_seat_id\":452,\"row\":\"Z\",\"seat\":\"2\",\"type\":\"seat\",\"stadium_block_name\":\"W1H\",\"block_id\":1,\"pricing_bands\":[{\"id\":1,\"price\":2.3,\"display_name\":\"Adult\",\"is_selected\":true}]}]","number_of_seats":1,"match_id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/prepare_checkout_for_ticket_purchase"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "consumer_card_id": 1,
    "tickets": "[{\"stadium_block_seat_id\":451,\"row\":\"Z\",\"seat\":\"1\",\"type\":\"seat\",\"stadium_block_name\":\"W1H\",\"block_id\":1,\"pricing_bands\":[{\"id\":1,\"price\":2.3,\"display_name\":\"Adult\",\"is_selected\":true}]},{\"stadium_block_seat_id\":452,\"row\":\"Z\",\"seat\":\"2\",\"type\":\"seat\",\"stadium_block_name\":\"W1H\",\"block_id\":1,\"pricing_bands\":[{\"id\":1,\"price\":2.3,\"display_name\":\"Adult\",\"is_selected\":true}]}]",
    "number_of_seats": 1,
    "match_id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/prepare_checkout_for_ticket_purchase`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `consumer_card_id` | integer |  required  | The id of card.
        `tickets` | json |  required  | A tickets data.
        `number_of_seats` | integer |  optional  | required.
        `match_id` | integer |  optional  | required. The id of match.
    
<!-- END_a8909584e26d359fc57eefd481f5929c -->

<!-- START_4759ac0d08d2c29d86036cef2100f693 -->
## Ticket payment
Check payment status and response of ticket.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/ticket_purchase_payment" \
    -H "Content-Type: application/json" \
    -d '{"ticket_transaction_id":1,"checkout_id":"1"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/ticket_purchase_payment"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "ticket_transaction_id": 1,
    "checkout_id": "1"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/ticket_purchase_payment`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `ticket_transaction_id` | integer |  required  | The id of ticket transaction.
        `checkout_id` | string |  required  | The checkout id of payment.
    
<!-- END_4759ac0d08d2c29d86036cef2100f693 -->

<!-- START_0cf1df3a9770d8c53159bd2bb0b8d976 -->
## Get user upcoming match ticket
Get user upcoming match ticket.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_user_upcoming_match_ticket" 
```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_user_upcoming_match_ticket"
);


fetch(url, {
    method: "POST",
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_user_upcoming_match_ticket`


<!-- END_0cf1df3a9770d8c53159bd2bb0b8d976 -->

<!-- START_cd7b248f6f18b9fc955771f297f42a2b -->
## Email tickets in pdf
Email tickets in pdf.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/email_match_tickets_in_pdf" \
    -H "Content-Type: application/json" \
    -d '{"ticket_transaction_id":1,"email":"abc@example.com"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/email_match_tickets_in_pdf"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "ticket_transaction_id": 1,
    "email": "abc@example.com"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/email_match_tickets_in_pdf`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `ticket_transaction_id` | integer |  required  | The id of ticket transaction.
        `email` | string |  required  | An email id of a user.
    
<!-- END_cd7b248f6f18b9fc955771f297f42a2b -->

<!-- START_e3d6a4dc833577f15acf163768d2a438 -->
## Get user ticket wallet
Get user ticket wallet.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_user_ticket_wallet_details" 
```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_user_ticket_wallet_details"
);


fetch(url, {
    method: "POST",
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_user_ticket_wallet_details`


<!-- END_e3d6a4dc833577f15acf163768d2a438 -->

<!-- START_01af65771c09a99201d6df84a1940163 -->
## Sell match ticket

Sell match ticket
.    *

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/sell_match_ticket" \
    -H "Content-Type: application/json" \
    -d '{"booked_ticket_id":1,"return_time_to_wallet":"72_hours_before","account_number":"14a","sort_code":231}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/sell_match_ticket"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "booked_ticket_id": 1,
    "return_time_to_wallet": "72_hours_before",
    "account_number": "14a",
    "sort_code": 231
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/sell_match_ticket`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `booked_ticket_id` | integer |  required  | An id of a booked ticket.
        `return_time_to_wallet` | enum |  required  | A return time to wallet.
        `account_number` | string |  required  | An account no .
        `sort_code` | integer |  required  | A short code.
    
<!-- END_01af65771c09a99201d6df84a1940163 -->

<!-- START_c0915e46317979b9a37b465e0101f36d -->
## Scan booked ticket

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/scan_ticket" \
    -H "Content-Type: application/json" \
    -d '{"ticket_id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/scan_ticket"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "ticket_id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/scan_ticket`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `ticket_id` | integer |  required  | An id of a booked ticket.
    
<!-- END_c0915e46317979b9a37b465e0101f36d -->

#Travel Information Pages


APIs for Travel Information Pages.
<!-- START_b32563e4e5e85828adb00d5341972c63 -->
## Get travel information pages
Get all published travel information pages.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/travel_information_pages" 
```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/travel_information_pages"
);


fetch(url, {
    method: "POST",
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/travel_information_pages`


<!-- END_b32563e4e5e85828adb00d5341972c63 -->

<!-- START_5b7f5792d725a74af325665303703205 -->
## Get travel offers and information pages
Get all published travel offers and information pages.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/travel_offers_and_information_pages" 
```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/travel_offers_and_information_pages"
);


fetch(url, {
    method: "POST",
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/travel_offers_and_information_pages`


<!-- END_5b7f5792d725a74af325665303703205 -->

#Travel Offers


APIs for Travel Offers.
<!-- START_72771a84a3d78d8570e52fb22c0aede0 -->
## Get travel special offers
Get all published travel special offers.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/travel_special_offers" 
```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/travel_special_offers"
);


fetch(url, {
    method: "POST",
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/travel_special_offers`


<!-- END_72771a84a3d78d8570e52fb22c0aede0 -->

#Travel Warnings


APIs for Travel Warnings.
<!-- START_2afc7b5b90b814e9e6d770a2e7a51d2d -->
## Get travel warnings
Get all published travel warnings.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/travel_warnings" 
```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/travel_warnings"
);


fetch(url, {
    method: "POST",
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/travel_warnings`


<!-- END_2afc7b5b90b814e9e6d770a2e7a51d2d -->

#Update feeds


APIs for feeds.
<!-- START_e40bea945720e5b46f8f99cc61059429 -->
## Get update feeds
Get all published update feeds of a club.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/get_update_feeds" \
    -H "Content-Type: application/json" \
    -d '{"club_id":1,"page":1,"per_page":5}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/get_update_feeds"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "club_id": 1,
    "page": 1,
    "per_page": 5
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/get_update_feeds`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `club_id` | integer |  required  | An id of a club.
        `page` | integer |  required  | Page number.
        `per_page` | integer |  required  | Number of records per page.
    
<!-- END_e40bea945720e5b46f8f99cc61059429 -->

<!-- START_7bb8f577e2b49c17d7bf2fe927d417aa -->
## Get RSS details
Get RSS details.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/rss_details" \
    -H "Content-Type: application/json" \
    -d '{"id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/rss_details"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/rss_details`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id` | integer |  required  | An id of a RSS feed.
    
<!-- END_7bb8f577e2b49c17d7bf2fe927d417aa -->

#User


APIs for User.
<!-- START_4949eaadf2ab2cfe3e245c5f56a352cc -->
## Send Device Token

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/send_device_token" \
    -H "Content-Type: application/json" \
    -d '{"device_token":"labore"}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/send_device_token"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "device_token": "labore"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/send_device_token`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `device_token` | text |  required  | 
    
<!-- END_4949eaadf2ab2cfe3e245c5f56a352cc -->

#Video


APIs for Video.
<!-- START_59ee96c738b1698066925e6b55db1f79 -->
## Get Videos
Get Videos.

> Example request:

```bash
curl -X POST \
    "http://mukesh-fanslive.dev.aecortech.com/api/videos" \
    -H "Content-Type: application/json" \
    -d '{"club_id":1}'

```

```javascript
const url = new URL(
    "http://mukesh-fanslive.dev.aecortech.com/api/videos"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "club_id": 1
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/videos`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `club_id` | integer |  required  | An id of a club.
    
<!-- END_59ee96c738b1698066925e6b55db1f79 -->


