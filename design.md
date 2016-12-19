# Grocery Service Design

## Overview
I am building this service because I want to:
* Store all of our recipes somewhere
* Easily add those ingredients to a shopping list
* Ensure that no duplicates are added to the shopping list - or no more is added than is required.
    * For example: If I need 1 red onion in one recipe and 2 red onions in another recipe then the shopping list will contain 3 red onions.
* It would be nice if the items would be added to the shoppig list in quantites that the shops sold
    * For exmaple: Shops sell onions in bags of 6, therefore if I have a requirement for 3 red onions overall then a bag of 6 onions will be added to the shopping list instead of 3.
    * It would also be nice if the endpoint returned both the amount that needs to be purchased (based on measurements that the shops sells) as well as the actual amount required.

## Model Overview

### Recipe
Contains all information about a recipe - this includes the name, a short description, all of the ingredients, the amounts required, how many it serves and the method.
```
Recipe(
	id: integer,
	name: string,
	description: string,
	method: string,
	serves: integer,
	ingredients: RecipeIngredient
)
```

### RecipeIngredient
An ingredient for a recipe. Contains details about an ingredient as well as an amount required for the current recipe.
```
RecipeIngredient(
	details: Ingredient
	amount: string
)
```

### Ingredient
Pretty much what is says on the tin. Just contains a name for now - but maybe it could include things like brands that sell it (and maybe a rating?) and what supermarket sells it / which isle to find it in.
```
Ingredient(
	id: integer,
	name: string
)
```

## Endpoint overview

_GET /recipes_
Retrieves all recipes

```
[
	{
		"id": 1,
		"name": "Ham Sandwich",
		"description": "The perfect blend between blandness and lazyness",
		"method": "Get bread, butter it then stick ham in the middle fool!",
		"serves": 1,
		"ingredients": [
		    {
				"details": {
				    "id": 1,
				    "name": "Bread"
				},
				"amount": "4 slices"
		    },
		    {
				"details": {
				    "id": 2,
				    "name": "Butter"
				},
				"amount": "2 Tablespoons"
		    },
		    {
				"details": {
				    "id": 3,
				    "name": "Ham"
				},
				"amount": "2 Tablespoons"
		    }
		]
	},
	{
		"id": 2,
		"name": "Ham Toastie",
		"description": "A little less lazy makes a little less bland.",
		"method": "Make a ham sandwich - but then like fry it or put it in a toastie maker or some shit.",
		"serves": 1,
		"ingredients": [
		    {
				"details": {
				    "id": 1,
				    "name": "Bread"
				},
				"amount": "4 slices"
		    },
		    {
				"details": {
				    "id": 2,
				    "name": "Butter"
				},
				"amount": "3 Tablespoons"
		    },
		    {
				"details": {
				    "id": 3,
				    "name": "Ham"
				},
				"amount": "2 Tablespoons"
		    }
		]
	}
]
```

_GET recipes/{id}
Retrieve one recipe
```
{
	"name": "Ham Sandwich",
	"description": "The perfect blend between blandness and lazyness",
	"method": "Get bread, butter it then stick ham in the middle fool!",
	"serves": 1,
	"ingredients": [
		{
			"details": {
				"id": 1,
				"name": "Bread"
			},
			"amount": "4 slices"
		},
		{
			"details": {
				"id": 2,
				"name": "Butter"
			},
			"amount": "2 Tablespoons"
		},
		{
			"details": {
				"id": 3,
				"name": "Ham"
			},
			"amount": "2 Tablespoons"
		}
	]
}
```

_POST /recipes_
Create a recipe
Request:
```
{
	"name": "Ham Sandwich",
	"description": "The perfect blend between blandness and lazyness",
	"method": "Get bread, butter it then stick ham in the middle fool!",
	"serves": 1
}
```
Response:
```
{
	"code": "success",
	"data": {
		"id": 123,
		"name": "Ham Sandwich",
		"description": "The perfect blend between blandness and lazyness",
		"method": "Get bread, butter it then stick ham in the middle fool!",
		"serves": 1
	}	
}
```

_PUT /recipes/{id}/ingredients_
Add an ingredient to a recipe
Request:
```
{
	"ingredientId": 1,
	"amount": "4 slices"
}
```
Response:
```
{
	"success": true,
	"data": {
		"details": {
			"id": 1,
			"name": "Bread"
		},
		"amount": "4 slices"
	}
}
```

_GET /ingredients_
Retrieve all ingredients
```
[
	{
		"id": 1,
		"name": "Bread"
	},
	{
		"id": 2,
		"name": "Ham"
	},
	{
		"id": 3
		"name": "Butter"
	},
]
```

_GET /ingredients/{id}_
Retrieve one ingredient
```
{
	"id": 1,
	"name": "Bread"
}
```

_POST /ingredients_
Request:
```
{
	"name": "Sausage"
}
```
Response:
```
{
	"code": "success",
	"data": {
		"id": 4,
		"name": "Sausage"
	}
}
```