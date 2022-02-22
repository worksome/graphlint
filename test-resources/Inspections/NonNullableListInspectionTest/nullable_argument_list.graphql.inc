type Person {
  friends(exclude: [ID]): [Person]!
}

-----
type Person {
  friends(exclude: [ID]!): [Person]!
}
