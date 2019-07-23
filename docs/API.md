# API Documentation

URI: https://flg-birthday.mccabecosta.com/api

**No authorization is required for this endpoint**

There are 3 request endpoints that have been programmed for this API:

## GET /api

### Request Variables

#### /api with no variables

HTTP Status Code: 200 OK

This will return an JSON object with all birthdays in the database.

#### /api?today=true

HTTP Status Code: 200 OK

This will return a JSON object with birthdays that are today

#### /api?today=false

HTTP Status Code: 200 OK

This will return a JSON object with birthdays that are not today

## POST /api

### Expected variables
Will return HTTP Status Code 400 Bad Request if not supplied

* **user_name** - (string) The user's name
* **user_dob** - (string) The user's DOB, preferentially formatted in ISO 8601 format (1970-01-01)

Will return HTTP Status Code 201 Created on Success

## DELETE /api

### Expected variables
Will return HTTP Status Code 400 Bad Request if not supplied

**entry_id** - (int) The entry ID of the birthday entry you wish to delete

Will return HTTP Status Code 204 No Content on success
