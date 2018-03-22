# Categories

## Retrieve

- `GET /api/rest/categories`
- `GET /api/rest/categories/store/:store`
- `GET /api/rest/categories/:id`
- `GET /api/rest/categories/:id/store/:store`
- `GET /api/rest/categories/parent/:parent`
- `GET /api/rest/categories/parent/:parent/store/:store`
- `GET /api/rest/category_trees`
- `GET /api/rest/category_trees/store/:store`

Results are filtered by `:store`.
If omitted by guests or customers the default store is assumed.
If omitted by admin users all categories are accessible, even if they are not assigned to any store.
Only admin users may see inactive categories.

If `:parent` is specified then only it's child categories are considered.
This is the same as filtering the attribute `parent_id`.

The `category_trees` resource is a special representation where only root categories are listed but each has a `children` field which is an embedded list of categories,
each of those may also have a `children` field and so on…
There is no paging nor sorting.
Child categories are already in `position` order.
Since guests and customers may only see categories relevant to their store,
and a store only has one root category,
then they will always retrieve one tree.
This resource is intended for fetching all categories in one request and so for very large sites might be slow and/or have memory limit problems.

### Attributes

- `available_sort_by`: Comma-separated list of sortable attribute codes.
- `children_count`: Number of entries in `/api/rest/categories/parent/:parent`.
- `custom_apply_to_products`: Boolean.
- `custom_design`
- `custom_design_from`
- `custom_design_to`
- `custom_layout_update`: An XML string.
- `custom_use_parent_settings`: Boolean.
- `default_sort_by`: A single sortable attribute code.
- `description`
- `display_mode`: One of "PRODUCTS", "PAGE", "PRODUCTS_AND_PAGE".
- `entity_id`: The ID of this category.
- `filter_price_range`
- `image`: Admin only. Name of image file in media catalog directory.
- `image_url`: Fully qualified URL.
- `include_in_menu`: Boolean.
- `is_active`: Boolean. Only visible to admin users.
- `is_anchor`: Boolean.
- `landing_page`: A [CMS block](https://github.com/clockworkgeek/Magento-Extra-RESTful/blob/master/docs/Blocks.md#cms-blocks) ID. Not applicable if `display_mode` is "PRODUCTS".
- `level`: A 'root' category is "1", it's children are all "2", and so on…
- `meta_description`
- `meta_keywords`
- `meta_title`
- `name`
- `page_layout`: One of "empty", "one_column", "two_columns_left", "two_columns_right", "three_columns", or any custom page layout handle.
- `parent_id`: The ID of this category's direct ancestor.
- `path`: A string like "1/2/3". Each number is a category ID, the first is the hidden root, the last is this category.
- `position`
- `product_count`: Number of entries in `/api/rest/categories/:category/products`.
- `thumbnail`: Only relevant to an XMLConnect feature since 1.9.2.4.
- `updated_at`
- `url`: Fully qualified URL. Not applicable to store "0" which only admin can see.
- `url_key`

### Category Products

A category's products are already available from `/api/rest/products/category/:category` but this ignores anchor categories with child categories.
The preferred alternative is the following resources which accurately match the `product_count` of an anchor category.

- `GET /api/rest/categories/:category/products`
- `GET /api/rest/categories/:category/products/store/:store`

The product records returned are Extra RESTful products.
See [docs/Products.md](https://github.com/clockworkgeek/Magento-Extra-RESTful/blob/master/docs/Products.md) for more information.

## Create / Update

- `POST /api/rest/categories/`
- `POST /api/rest/categories/store/:store`
- `PUT /api/rest/categories/:id`
- `PUT /api/rest/categories/:id/store/:store`

Admin only.

### Attributes

The most important here are `name`, `parent_id` and `url_key`.

- `available_sort_by`: Comma-separated list of sortable attribute codes.
- `custom_apply_to_products`: Boolean.
- `custom_design`
- `custom_design_from`
- `custom_design_to`
- `custom_layout_update`: An XML string.
- `custom_use_parent_settings`: Boolean.
- `default_sort_by`: A single sortable attribute code.
- `description`
- `display_mode`: One of "PRODUCTS", "PAGE", "PRODUCTS_AND_PAGE".
- `filter_price_range`
- `image`
- `include_in_menu`: **Required**. Boolean.
- `is_active`: **Required**. Boolean.
- `is_anchor`: Boolean.
- `landing_page`: A [CMS block](https://github.com/clockworkgeek/Magento-Extra-RESTful/blob/master/docs/Blocks.md#cms-blocks) ID. Not applicable if `display_mode` is "PRODUCTS".
- `meta_description`
- `meta_keywords`
- `meta_title`
- `name`: **Required**.
- `page_layout`: One of "empty", "one_column", "two_columns_left", "two_columns_right", "three_columns", or any custom page layout handle.
- `parent_id`: **Required**. To create a 'root' category this should be "1" which is the real root category ID.
- `position`: Integer.
- `thumbnail`: Only relevant to an XMLConnect feature since 1.9.2.4.
- `url_key`

## Delete

- `DELETE /api/rest/categories/:id`
