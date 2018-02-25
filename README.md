# Extra RESTful

Developers are invited to include this reusable extension in their projects for better connectivity.
Extra RESTful is permissively licensed for this reason.
Suggestions for more resources to support are welcome,
just raise an [issue](https://github.com/clockworkgeek/Magento-Extra-RESTful/issues).

### New Attributes

- **Catalog Product / Read / Created At** (`created_at`)
- **Catalog Product / Read / Updated At** (`updated_at`)
- **Catalog Product / Read / Has Options** (`has_options`)
- **Catalog Product / Read / Has Required Options** (`required_options`)
- **Customer / Read / Updated At** (`updated_at`)

### New Resources

- **Core / Store View / Retrieve**
  - `GET /api/rest/stores`
- **Core / Custom URL Rewrites / Retrieve**
  - `GET /api/rest/rewrites`
  - `GET /api/rest/rewrites/store/:store`
- **Catalog / Category / Create**
  - `POST /api/rest/categories`
  - `POST /api/rest/categories/store/:store`
- **Catalog / Category / Retrieve**
  - `GET /api/rest/categories`
  - `GET /api/rest/categories/store/:store`
  - `GET /api/rest/categories/parent/:parent`
  - `GET /api/rest/categories/parent/:parent/store/:store`
  - `GET /api/rest/categories/:id`
  - `GET /api/rest/categories/:id/store/:store`
  - `GET /api/rest/category_trees`  
  (Category tree is a convenient representation  of a normally flat list.
  Its access can be controlled as a separate resource but uses the same attributes.
  [Page size and sort order](http://devdocs.magento.com/guides/m1x/api/rest/get_filters.html) do not work on trees.)
  - `GET /api/rest/category_trees/store/:store`  
  (Categories can be filtered by the store they are assigned to.)
- **Catalog / Category / Update**
  - `PUT /api/rest/categories/:id`
  - `PUT /api/rest/categories/:id/store/:store`
- **Catalog / Category / Delete**
  - `DELETE /api/rest/categories/:id`
- **Catalog / Review / Create**
  - `POST /api/rest/reviews`
  - `POST /api/rest/reviews/store/:store`
  - `POST /api/rest/reviews/product/:product`
  - `POST /api/rest/reviews/product/:product/store/:store`
  - `POST /api/rest/products/:id/reviews`
- **Catalog / Review / Retrieve**
  - `GET /api/rest/reviews`
  - `GET /api/rest/reviews/:id`
  - `GET /api/rest/reviews/store/:store`
  - `GET /api/rest/reviews/product/:product`
  - `GET /api/rest/reviews/product/:product/store/:store`
  - `GET /api/rest/products/:id/reviews`
- **Catalog / Review / Update**
  - `PUT /api/rest/reviews`
  - `PUT /api/rest/reviews/:id`
- **Catalog / Review / Delete**
  - `DELETE /api/rest/reviews/:id`
- **CMS / Block / Create**
  - `POST /api/rest/cms/blocks`
- **CMS / Block / Retrieve**
  - `GET /api/rest/cms/blocks`
  - `GET /api/rest/cms/blocks/:id`
  - `GET /api/rest/cms/blocks/:id/store/:store`  
  (`:id` can be number or 'identifier'. Will output as HTML if "text/html" is in the Accept header)
- **CMS / Block / Update**
  - `PUT /api/rest/cms/blocks/:id`
- **CMS / Block / Delete**
  - `DELETE /api/rest/cms/blocks/:id`
  