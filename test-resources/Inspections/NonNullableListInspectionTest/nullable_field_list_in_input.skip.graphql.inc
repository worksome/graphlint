input NameInput {
  firstName: String!
}

input PersonInput {
  friends: [NameInput!]
}
