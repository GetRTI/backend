# getRTI API (http://www.getrti.org/api)

Send HTTP requests and get JSON responses. All the CRUD functionality of the application has been covered in this API. In general, the following HTTP requests are supported.

  - GET model - list all the items in a model, eg GET files
  - GET model/id - view info about a single item in a model, eg GET files/1
  - POST model - create a new item in a model, eg POST files to create a new file
  - PUT model/id - edit an existing item in a model, eg PUT files/1 to edit/update file description of id 1
  - DELETE model/id - to delete an item in a model, eg DELETE files/1 

Individual models and their requets has been discussed below

## files

the main api dealing with addition, updation, and deletion of RTI files

### POST files

Send the POST request to (http://www.getrti.org/api/files) with the following $_POST variables

   - name - file name
   - file - the file, should be pdf, jpg or png
   - tags - comma separated list of tags (optional)

Example response:

```
{
    "fileID":"7",
    "slug":"test-file-62714"
}
```
   - fileID - id of the new file created in db
   - slug - URL slug of the file, useful if you redirect users to the file page immediately after upload

Errors:

```
{
    "error":"Incomplete Request"
}
```
Either name or file is missing

```
{
    "error":"File mime type not allowed"
}
```
File mime type is not allowed

### GET files

Send a GET request to (http://www.getrti.org/api/files) to get a list of files

Example response:
```
[
    {
        "id":"4",
        "name":"test file",
        "department":null,
        "description":null,
        "content":null,
        "address":"files\/zyQFNJurOstQuFcL.pdf",
        "date":"2014-01-10 15:02:18",
        "slug":"test-file-90112",
        "published":"0",
        "uploaded_by":null
    },
    {
        "id":"5",
        "name":"test file",
        "department":null,
        "description":null,
        "content":null,
        "address":"files\/akCTrRRDAMvEQbfA.pdf",
        "date":"2014-01-10 15:05:59",
        "slug":"test-file-20278",
        "published":"0",
        "uploaded_by":null
    }
]
```

# Attributions


[1] AngularJs 
[2] Twitter BootStrap
[3] Yii Framework
[4] Mysql
[5] Tesseract