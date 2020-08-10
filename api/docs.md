FORMAT: 1A

# Example

# Cats [/cats]

## Show all cats [GET /cats]
Get a JSON representation of all the cats

+ Response 200 (application/json)
    + Body

            {
                "data": " Array<Cat> "
            }

## Get Cat Details [GET /cats/{id}]


+ Response 200 (application/json)
    + Body

            {
                "data": " Cat"
            }

# posts [/posts]

## Show latest posts [GET /posts]
Get a JSON representation of latest posts

+ Response 200 (application/json)
    + Body

            {
                "data": "Array<Post>"
            }

## Show latest posts [GET /posts/cat/{id}]
Get a JSON representation of latest posts by cat {id}

+ Response 200 (application/json)
    + Body

            {
                "data": "Array<Post>"
            }

## Show Post Details [POST /posts]
Get a JSON representation of Post Details by post {id}

+ Request (application/x-www-form-urlencoded)
    + Body

            id=:number

+ Response 200 (application/json)
    + Body

            {
                "data": "Post[info+comment+files]"
            }