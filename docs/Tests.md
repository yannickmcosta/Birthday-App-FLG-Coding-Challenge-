# Testing

## Form tests

|Test                         |Expected Outcome                                    |Pass|Notes                    |
|-----------------------------|----------------------------------------------------|----|-------------------------|
|No entry on form             |HTML5 Validation prevents form submission           |YES |Both form fields required|
|No date supplied on form     |HTML5 Validation prevents form submission           |YES |Both form fields required|
|No name supplied on form     |HTML5 Validation prevents form submission           |YES |Both form fields required|
|Invalid date supplied on form|HTML5 Datepicker validation prevents form submission|YES |                         |
|Both name and date supplied  |Form submits sucessfully, and data is added to DB   |YES |                         |

## API Tests


#### GET Requests

|Test                                   |Expected Outcome      |Pass|Notes                                                                     |
|---------------------------------------|----------------------|----|--------------------------------------------------------------------------|
|GET request with no variables set      |HTTP/2 200 OK         |YES |JSON response with birthday data                                          |
|GET request with numeric limit         |HTTP/2 200 OK         |YES |JSON response with birthday data limited to X records                     |
|GET request with numeric offset        |HTTP/2 200 OK         |YES |JSON response with birthday data starting at X offset                     |
|GET request with numeric limit & offset|HTTP/2 200 OK         |YES |JSON response with birthday data limited to X records starting at Y offset|
|GET request with non-numeric limit     |HTTP/2 400 Bad Request|YES |API returns an error stating a non-numeric limit was supplied             |
|GET request with non-numeric offset    |HTTP/2 400 Bad Request|YES |API returns an error stating a non-numeric offset was supplied            |
|GET request with today var set TRUE    |HTTP/2 200 OK         |YES |JSON response with birthday data for today only                           |
|GET request with today var set FALSE   |HTTP/2 200 OK         |YES |JSON response with birthday data for all other days, except today         |
|GET request with today var set NULL    |HTTP/2 200 OK         |YES |JSON response with birthday data, handled the same as if this wasn't set  |


#### POST Requests

|Test                                         |Expected Outcome      |Pass|Notes                                                          |
|---------------------------------------------|----------------------|----|---------------------------------------------------------------|
|POST request with user_name and user_dob set |HTTP/2 201 Created    |YES |                                                               |
|POST request with no data                    |HTTP/2 400 Bad Request|YES |API returns an error stating no post data was supplied         |
|POST request with no/empty user_name         |HTTP/2 400 Bad Reqeust|YES |API returns an error stating no user name was supplied         |
|POST request with no/empty user_dob          |HTTP/2 400 Bad Request|YES |API returns an error stating no user dob was supplied          |
|POST request with malformed date for user_dob|HTTP/3 400 Bad Request|YES |API returns an error stating an invalid datestring was supplied|            
#### DELETE Requests

|Test                                    |Expected Outcome          |Pass|Notes                                                     |
|----------------------------------------|------------------------- |----|----------------------------------------------------------|
|DELETE request with entry_id set        |HTTP/2 204 No Content     |YES |                                                          |
|DELETE request with no data supplied    |HTTP/2 400 Bad Reqeust    |YES |API returns an error stating no entry ID was supplied     |
|DELETE request with non-numeric entry_id|HTTP/2 400 Bad Request    |YES |API returns an error stating a non-numeric ID was supplied|

#### Non supported methods

API returned a HTTP/2 405 Method Not Allowed