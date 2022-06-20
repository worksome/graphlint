type Query {
  invoices(
    accounts: [ID!]
  ): [Invoice!]!
}