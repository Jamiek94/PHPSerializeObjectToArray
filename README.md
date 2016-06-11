# PHPSerializeObjectToArray
PHP Serializer which converts an object to an array that can be encoded to JSON.

Here is an example with an review object which contains two methods which returns an integer and an array of comments.

**Review**

 - getAmountReviews : int
 - getReviews : array of comments

**Comment**

 - getSubject : string
 - getDescription : string

Will be transformed into:

        {
          amount_reviews: 21,
          reviews: [
            {
              subject: "In een woord top 1!",
              description: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque laoreet lacus quis eros venenatis, sed tincidunt mi rhoncus. Aliquam ut pharetra diam, nec lobortis dolor."
            },
            {
              subject: "En een zwembad 2!",
              description: "Maecenas et aliquet mi, a interdum mauris. Donec in egestas sem. Sed feugiat commodo maximus. Pellentesque porta consectetur commodo. Duis at finibus urna."
            },
            {
              subject: "In een woord top 3!",
              description: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque laoreet lacus quis eros venenatis, sed tincidunt mi rhoncus. Aliquam ut pharetra diam, nec lobortis dolor."
            },
            {
              subject: "En een zwembad 4!",
              description: "Maecenas et aliquet mi, a interdum mauris. Donec in egestas sem. Sed feugiat commodo maximus. Pellentesque porta consectetur commodo. Duis at finibus urna."
           },
           {
              subject: "In een woord top 5!",
              description: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque laoreet lacus quis eros venenatis, sed tincidunt mi rhoncus. Aliquam ut pharetra diam, nec lobortis dolor."
        }
    ]}

All you have to do is wrap json_encode around the output.

**Some information about the script:**

 - Only methods which starts with get are added
 - Private methods are ignored
 - Constructors are ignored
 - Capital characters in the method name will be replaced with an underscore and lowercased character
