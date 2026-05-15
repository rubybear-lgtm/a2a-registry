from collections.abc import Mapping
from typing import Any, TypeVar, Optional, BinaryIO, TextIO, TYPE_CHECKING, Generator

from attrs import define as _attrs_define
from attrs import field as _attrs_field

from ..types import UNSET, Unset







T = TypeVar("T", bound="PaginationMeta")



@_attrs_define
class PaginationMeta:
    """ 
        Attributes:
            current_page (int):
            from_ (int):
            last_page (int):
            path (str):
            per_page (int):
            to (int):
            total (int):
     """

    current_page: int
    from_: int
    last_page: int
    path: str
    per_page: int
    to: int
    total: int
    additional_properties: dict[str, Any] = _attrs_field(init=False, factory=dict)





    def to_dict(self) -> dict[str, Any]:
        current_page = self.current_page

        from_ = self.from_

        last_page = self.last_page

        path = self.path

        per_page = self.per_page

        to = self.to

        total = self.total


        field_dict: dict[str, Any] = {}
        field_dict.update(self.additional_properties)
        field_dict.update({
            "current_page": current_page,
            "from": from_,
            "last_page": last_page,
            "path": path,
            "per_page": per_page,
            "to": to,
            "total": total,
        })

        return field_dict



    @classmethod
    def from_dict(cls: type[T], src_dict: Mapping[str, Any]) -> T:
        d = dict(src_dict)
        current_page = d.pop("current_page")

        from_ = d.pop("from")

        last_page = d.pop("last_page")

        path = d.pop("path")

        per_page = d.pop("per_page")

        to = d.pop("to")

        total = d.pop("total")

        pagination_meta = cls(
            current_page=current_page,
            from_=from_,
            last_page=last_page,
            path=path,
            per_page=per_page,
            to=to,
            total=total,
        )


        pagination_meta.additional_properties = d
        return pagination_meta

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
