# Utils

Generic utility classes for application development


## Installation

This library requires PHP 5.3 or later. It is available and downloadable as a custom repository through composer.

## Quality

[![Travis](https://api.travis-ci.org/maldoinc/utils.svg)](https://travis-ci.org/maldoinc/utils)


## Classes

### Pagination

Provides easy generation of pagination content for large datasets.

```php
$pagination = new Pagination($total_rows, $rows_per_page, $current_page, $visible_pages);

$pagination_html = $pagination->getHTML(function ($page, $label) {
    return sprintf("<li><a href='some/data?p=%d'></a></li>", $page, $label);
});


// somewhere in your favourite templating engine

<ul> {{ pagination_html }} </ul>

```


### SimplePagination

Simplifies the pagination class even further. Constructor accepts 3 arguments

`total_rows`, `current_page`, and `pagination_url` which serves as a formatting string to replace page numbers into. Place a `%d` symbol at the place where you want your page number to be put. The generated HTML is Bootstrap-ready.



```php
$pagination_html = new SimplePagination($total_rows, $current_page, "users/list?p=%d");
```

That was too easy now, was it?


### Cart

Allows management of a shopping cart (no way!)


##### Add an item to the cart

The add method returns a unique string which can be used to access this specific item in the future.
The data property is meant to be used as a means to store any information related to the added item.
```php
$rowid = $cart->add($item_identifier, /* $data */ ['size' => 'M'], $pice, $quantity);
```


##### Remove an item from the cart

This will remove an item from the cart variable.
```php
$cart->remove($rowid);
```

##### Update an item

Update an item's rowid or data

```php
$cart->update($rowid, $new_quantity); // this will only update the quantity

$cart->update($rowid, $new_quantity, ['size' => 'L']); // will also update data
```

##### Get an item from the shopping cart

```php
$item = $cart->get($rowid);
```

`$item` variable is of the `CartItem` type and it supports the following properties:

* rowId - _denotes the rowid assigned to it by the parent `Cart` class_
* identifier
* quantity
* price
* data

###### Nb: The `update`, `remove` and `get` methods will throw an `ItemNotFoundException` in case the specified rowid is not found. If you want to safely check the existence of a rowid use the `has` method


### Persistent Cart

All of the above is nice and all but sort of not useful if you can't persist the data across requests. To our rescue comes the `CartPersistentInterface` interface, which is passed to the `Cart` class constructor.

Out of the box the following persistence methods are supported

* PHP Native session storage via the `SessionPersistenceStrategy` class
* File based sessions via the `FilePersistenceStrategy` class

You can implement your own storage mechanisms by implementing the persistence interface and passing the class to the `Cart` constructor.

```php
// session based persistence
$cart = new Cart(new SessionPersistenceStrategy('some_unique_session_key'));

// file based
$cart = new Cart(new FilePersistenceStrategy("temp/sessions/" . $user_id));
```


### Session Management

The default cookie-based sessions tend to be a mess when multiple applications hosted on the same domain try and access the same keys ending up overwriting each-other's data. This problem is solved by the `SessionManager` class.

```php
$session = new SessionManager('some_unique_session_key');
```

The session manager supports the following methods

* `get($key, $default = null)` - get an item
* `set($key, $value)` - set an item
* `forget($key)` - remove an item
* `pull($key, $default = null)` - get an item and remove it
* `flush` - clear all stored data
* `all` - get all the stored data
* `has($key)` - check existence

If of course you do not want to use the actual session for this, but would rather have database based, file based or whatever based sessions, really - you could implement `SessionManagementInterface` and then simply create an instance of your class rather than a `SessionManager` which should mean that your application works with just this change. Preferably you want to use a singleton pattern alongside this to make sure that you only have to replace one line in the whole application.