union _Entity = User

scalar _FieldSet

type _Service {
  sdl: String
}

"""
Representation of entities from external services for the root `_entities` field.
"""
scalar _Any

"""
Indicates what fields are available at the top level of a query operation.
"""
type Query {
  _entities(representations: [_Any!]!): [_Entity]!
  _service: _Service!
}
