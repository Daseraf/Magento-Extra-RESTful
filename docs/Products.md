# Products

Extra RESTful replaces [Magento's products resource](http://devdocs.magento.com/guides/m1x/api/rest/Resources/Products/products.html) to provide the following benefits:

- Dropdown and multiselect type attributes are replaced with and filtered by their localised values.
- Product URLs are correct for the specified store.
- Flat tables are used for performance.
- Non-visible attributes are not loaded in the background, again for performance.
- Boolean and integer attributes are cast to native types for the convenience of JSON users.
- No `buy_now_url` because it's not RESTful.
- `updated_at` attribute so clients may filter the most recent and update their local copies efficiently.
- `required_options` attribute so clients may know if a product can be ordered immediately without further input.

Extra RESTful products are accessed at the same URI, `/api/rest/products`, but are designated "Version 2".
Admin users still access "Version 1" for now.
If they prefer, frontend users may access the old resource by adding a "Version" HTTP header in their client, e.g.

```http
GET /api/rest/products HTTP/1.1
Accept: */*
Host: example.com
Version: 1
```

## Product Custom Options

- `GET /api/rest/products/:product/options`
- `GET /api/rest/products/:product/options/store/:store`

This resource exposes the "Custom Options" as entered by admin for a particular product.
The product specified by `:product` must exist.

### Attributes

- `file_extension`: Comma- or space-separated list of allowed file extensions. Only applicable if `type` is `file`.
- `image_size_x`: A numeric value measured in pixels.  Only applicable if `type` is `file` and the uploaded file is an image.
- `image_size_y`: A numeric value measured in pixels.  Only applicable if `type` is `file` and the uploaded file is an image.
- `is_require`: Either "0" or "1".
- `max_characters`: A numeric value.  Only applicable if `type` is `field` or `area`.
- `option_id`: Use this ID when adding the product to cart.
- `price`: An optional float value to be added to the final price.  Not applicable if `type` is `drop_down`, `radio`, `checkbox`, or `multiple`.
- `price_type`: One of these values; "fixed", "percent".
- `sku`: A string that will be appended to the product's SKU if this option is used.
- `title`: Store-specific text to be displayed to end user.
- `type`: One of these values; "field", "area", "file", "drop_down", "radio", "checkbox", "multiple", "date", "date_time", "time".
- `values`: A list of objects if `type` is `drop_down`, `radio`, `checkbox`, or `multiple`.  Each has these attributes:
  - `price`: An optional float value to be added to the final price.
  - `price_type`: One of these values; "fixed", "percent".
  - `sku`: A string that will be appended to the product's SKU if this value is selected.
  - `title`: Store-specific text to be displayed to end user.
  - `value`: Use this ID when adding product to cart.

```json
[
    {
        "is_require": "0",
        "option_id": "2",
        "sku": null,
        "sort_order": "3",
        "title": "Test Custom Options",
        "type": "drop_down",
        "values": [
            {
                "price": "59.0000",
                "price_type": "fixed",
                "sku": "m1",
                "sort_order": "0",
                "title": "model 1",
                "value": "1"
            },
            {
                "price": "60.0000",
                "price_type": "fixed",
                "sku": "m2",
                "sort_order": "0",
                "title": "model 2",
                "value": "2"
            }
        ]
    }
]
```

## Related Products / Up-sells / Cross-sells

- `GET /api/rest/products/:product/related`
- `GET /api/rest/products/:product/related/store/:store`
- `GET /api/rest/products/:product/upsells`
- `GET /api/rest/products/:product/upsells/store/:store`
- `GET /api/rest/products/:product/crosssells/`
- `GET /api/rest/products/:product/crosssells/store/:store`

Lists products set as "Related Products", "Up-sells", or "Cross-sells", and in order of "Position" as set by the admin.
These lists behave exactly like `/api/rest/products` so can be filtered, ordered, and paged too.

## Associated Products

- `GET /api/rest/products/:product/associated`
- `GET /api/rest/products/:product/associated/store/:store`

Only for "Grouped" and "Configurable" products.
Simple products associated with a "Grouped" product contain an extra `qty` field which is the admin entered default quantity value.
"Configurable" products requested at `/api/rest/products/:id` contains an extra `super_attributes` object with keys that are attribute names and values that are printable labels.  A typical exchange might go like this:

```
GET /api/rest/products/410?attrs=name,description,super_attributes HTTP/1.1
Accept: */*
Host: example.com

HTTP/1.1 200 OK
Content-Length: 151
Content-Type: application/json; charset=utf-8


{
    "name": "Chelsea Tee",
    "description": "Ultrasoft, lightweight V-neck tee. 100% cotton. Machine wash.",
    "super_attributes": {
        "color": "Color",
        "size": "Size"
    }
}

GET /api/rest/products/410/associated?attrs=color,size,regular_price_without_tax HTTP/1.1
Accept: */*
Host: example.com

HTTP/1.1 200 OK
Content-Length: 241
Content-Type: application/json; charset=utf-8

[
    {
        "color": "Black",
        "size": "S",
        "regular_price_without_tax": 55
    },
    {
        "color": "Black",
        "size": "L",
        "regular_price_without_tax": 75
    },
    {
        "color": "White",
        "size": "M",
        "regular_price_without_tax": 65
    },
    {
        "color": "White",
        "size": "L",
        "regular_price_without_tax": 75
    }
]
```

This is enough information to display a form with fields for "Color" ("Black" or "White") and "Size" ("S", "M" or "L") and to update the price dynamically.
