from collections.abc import Mapping
from typing import Any, TypeVar, Optional, BinaryIO, TextIO, TYPE_CHECKING, Generator

from attrs import define as _attrs_define
from attrs import field as _attrs_field

from ..types import UNSET, Unset

from ..types import UNSET, Unset
from typing import cast
from typing import Union

if TYPE_CHECKING:
  from ..models.o_auth_2_security_scheme_flows import OAuth2SecuritySchemeFlows





T = TypeVar("T", bound="OAuth2SecurityScheme")



@_attrs_define
class OAuth2SecurityScheme:
    """ 
        Attributes:
            flows (Union[Unset, OAuth2SecuritySchemeFlows]):
     """

    flows: Union[Unset, 'OAuth2SecuritySchemeFlows'] = UNSET
    additional_properties: dict[str, Any] = _attrs_field(init=False, factory=dict)





    def to_dict(self) -> dict[str, Any]:
        from ..models.o_auth_2_security_scheme_flows import OAuth2SecuritySchemeFlows
        flows: Union[Unset, dict[str, Any]] = UNSET
        if not isinstance(self.flows, Unset):
            flows = self.flows.to_dict()


        field_dict: dict[str, Any] = {}
        field_dict.update(self.additional_properties)
        field_dict.update({
        })
        if flows is not UNSET:
            field_dict["flows"] = flows

        return field_dict



    @classmethod
    def from_dict(cls: type[T], src_dict: Mapping[str, Any]) -> T:
        from ..models.o_auth_2_security_scheme_flows import OAuth2SecuritySchemeFlows
        d = dict(src_dict)
        _flows = d.pop("flows", UNSET)
        flows: Union[Unset, OAuth2SecuritySchemeFlows]
        if isinstance(_flows,  Unset):
            flows = UNSET
        else:
            flows = OAuth2SecuritySchemeFlows.from_dict(_flows)




        o_auth_2_security_scheme = cls(
            flows=flows,
        )


        o_auth_2_security_scheme.additional_properties = d
        return o_auth_2_security_scheme

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
