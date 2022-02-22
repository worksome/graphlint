type User @model {
  name: String!
}
-----
type User @model {
  id: ID!
  name: String!
}
