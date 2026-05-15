from collections.abc import Mapping
from typing import Any, TypeVar, Optional, BinaryIO, TextIO, TYPE_CHECKING, Generator

from attrs import define as _attrs_define
from attrs import field as _attrs_field

from ..types import UNSET, Unset

from typing import cast

if TYPE_CHECKING:
  from ..models.validation_error_response_errors import ValidationErrorResponseErrors





T = TypeVar("T", bound="ValidationErrorResponse")



@_attrs_define
class ValidationErrorResponse:
    """ 
        Attributes:
            message (str):
            errors (ValidationErrorResponseErrors):
     """

    message: str
    errors: 'ValidationErrorResponseErrors'
    additional_properties: dict[str, Any] = _attrs_field(init=False, factory=dict)





    def to_dict(self) -> dict[str, Any]:
        from ..models.validation_error_response_errors import ValidationErrorResponseErrors
        message = self.message

        errors = self.errors.to_dict()


        field_dict: dict[str, Any] = {}
        field_dict.update(self.additional_properties)
        field_dict.update({
            "message": message,
            "errors": errors,
        })

        return field_dict



    @classmethod
    def from_dict(cls: type[T], src_dict: Mapping[str, Any]) -> T:
        from ..models.validation_error_response_errors import ValidationErrorResponseErrors
        d = dict(src_dict)
        message = d.pop("message")

        errors = ValidationErrorResponseErrors.from_dict(d.pop("errors"))




        validation_error_response = cls(
            message=message,
            errors=errors,
        )


        validation_error_response.additional_properties = d
        return validation_error_response

    @property
    def additional_keys(self) -> list[str]:
        return list(self.additional_properties.keys())

    def __getitem__(self, key: str) -> Any:
        return self.additional_properties[key]

    def __setitem__(self, key: str, value: Any) -> None:
        self.additional_properties[key] = value

    def __delitem__(self, key: str) -> None:
        del self.additional_properties[key]

    def __contains__(self, key: str) -> bool:
        return key in self.additional_properties
