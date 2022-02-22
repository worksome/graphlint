type Mutation {
  version(environment: String!): String!
}

-----
type Mutation {
  version(input: String!): String!
}
