from collections.abc import Mapping
from typing import Any, TypeVar, Optional, BinaryIO, TextIO, TYPE_CHECKING, Generator

from attrs import define as _attrs_define
from attrs import field as _attrs_field

from ..types import UNSET, Unset

from ..types import UNSET, Unset
from typing import cast
from typing import Union

if TYPE_CHECKING:
  from ..models.o_auth_2_security_scheme import OAuth2SecurityScheme
  from ..models.api_key_security_scheme import ApiKeySecurityScheme
  from ..models.mutual_tls_security_scheme import MutualTlsSecurityScheme
  from ..models.http_auth_security_scheme import HttpAuthSecurityScheme
  from ..models.open_id_connect_security_scheme import OpenIdConnectSecurityScheme





T = TypeVar("T", bound="AgentCardSecurityScheme")



@_attrs_define
class AgentCardSecurityScheme:
    """ 
        Attributes:
            api_key_security_scheme (Union[Unset, ApiKeySecurityScheme]):
            http_auth_security_scheme (Union[Unset, HttpAuthSecurityScheme]):
            oauth_2_security_scheme (Union[Unset, OAuth2SecurityScheme]):
            open_id_connect_security_scheme (Union[Unset, OpenIdConnectSecurityScheme]):
            mtls_security_scheme (Union[Unset, MutualTlsSecurityScheme]):
     """

    api_key_security_scheme: Union[Unset, 'ApiKeySecurityScheme'] = UNSET
    http_auth_security_scheme: Union[Unset, 'HttpAuthSecurityScheme'] = UNSET
    oauth_2_security_scheme: Union[Unset, 'OAuth2SecurityScheme'] = UNSET
    open_id_connect_security_scheme: Union[Unset, 'OpenIdConnectSecurityScheme'] = UNSET
    mtls_security_scheme: Union[Unset, 'MutualTlsSecurityScheme'] = UNSET
    additional_properties: dict[str, Any] = _attrs_field(init=False, factory=dict)





    def to_dict(self) -> dict[str, Any]:
        from ..models.o_auth_2_security_scheme import OAuth2SecurityScheme
        from ..models.api_key_security_scheme import ApiKeySecurityScheme
        from ..models.mutual_tls_security_scheme import MutualTlsSecurityScheme
        from ..models.http_auth_security_scheme import HttpAuthSecurityScheme
        from ..models.open_id_connect_security_scheme import OpenIdConnectSecurityScheme
        api_key_security_scheme: Union[Unset, dict[str, Any]] = UNSET
        if not isinstance(self.api_key_security_scheme, Unset):
            api_key_security_scheme = self.api_key_security_scheme.to_dict()

        http_auth_security_scheme: Union[Unset, dict[str, Any]] = UNSET
        if not isinstance(self.http_auth_security_scheme, Unset):
            http_auth_security_scheme = self.http_auth_security_scheme.to_dict()

        oauth_2_security_scheme: Union[Unset, dict[str, Any]] = UNSET
        if not isinstance(self.oauth_2_security_scheme, Unset):
            oauth_2_security_scheme = self.oauth_2_security_scheme.to_dict()

        open_id_connect_security_scheme: Union[Unset, dict[str, Any]] = UNSET
        if not isinstance(self.open_id_connect_security_scheme, Unset):
            open_id_connect_security_scheme = self.open_id_connect_security_scheme.to_dict()

        mtls_security_scheme: Union[Unset, dict[str, Any]] = UNSET
        if not isinstance(self.mtls_security_scheme, Unset):
            mtls_security_scheme = self.mtls_security_scheme.to_dict()


        field_dict: dict[str, Any] = {}
        field_dict.update(self.additional_properties)
        field_dict.update({
        })
        if api_key_security_scheme is not UNSET:
            field_dict["apiKeySecurityScheme"] = api_key_security_scheme
        if http_auth_security_scheme is not UNSET:
            field_dict["httpAuthSecurityScheme"] = http_auth_security_scheme
        if oauth_2_security_scheme is not UNSET:
            field_dict["oauth2SecurityScheme"] = oauth_2_security_scheme
        if open_id_connect_security_scheme is not UNSET:
            field_dict["openIdConnectSecurityScheme"] = open_id_connect_security_scheme
        if mtls_security_scheme is not UNSET:
            field_dict["mtlsSecurityScheme"] = mtls_security_scheme

        return field_dict



    @classmethod
    def from_dict(cls: type[T], src_dict: Mapping[str, Any]) -> T:
        from ..models.o_auth_2_security_scheme import OAuth2SecurityScheme
        from ..models.api_key_security_scheme import ApiKeySecurityScheme
        from ..models.mutual_tls_security_scheme import MutualTlsSecurityScheme
        from ..models.http_auth_security_scheme import HttpAuthSecurityScheme
        from ..models.open_id_connect_security_scheme import OpenIdConnectSecurityScheme
        d = dict(src_dict)
        _api_key_security_scheme = d.pop("apiKeySecurityScheme", UNSET)
        api_key_security_scheme: Union[Unset, ApiKeySecurityScheme]
        if isinstance(_api_key_security_scheme,  Unset):
            api_key_security_scheme = UNSET
        else:
            api_key_security_scheme = ApiKeySecurityScheme.from_dict(_api_key_security_scheme)




        _http_auth_security_scheme = d.pop("httpAuthSecurityScheme", UNSET)
        http_auth_security_scheme: Union[Unset, HttpAuthSecurityScheme]
        if isinstance(_http_auth_security_scheme,  Unset):
            http_auth_security_scheme = UNSET
        else:
            http_auth_security_scheme = HttpAuthSecurityScheme.from_dict(_http_auth_security_scheme)




        _oauth_2_security_scheme = d.pop("oauth2SecurityScheme", UNSET)
        oauth_2_security_scheme: Union[Unset, OAuth2SecurityScheme]
        if isinstance(_oauth_2_security_scheme,  Unset):
            oauth_2_security_scheme = UNSET
        else:
            oauth_2_security_scheme = OAuth2SecurityScheme.from_dict(_oauth_2_security_scheme)




        _open_id_connect_security_scheme = d.pop("openIdConnectSecurityScheme", UNSET)
        open_id_connect_security_scheme: Union[Unset, OpenIdConnectSecurityScheme]
        if isinstance(_open_id_connect_security_scheme,  Unset):
            open_id_connect_security_scheme = UNSET
        else:
            open_id_connect_security_scheme = OpenIdConnectSecurityScheme.from_dict(_open_id_connect_security_scheme)




        _mtls_security_scheme = d.pop("mtlsSecurityScheme", UNSET)
        mtls_security_scheme: Union[Unset, MutualTlsSecurityScheme]
        if isinstance(_mtls_security_scheme,  Unset):
            mtls_security_scheme = UNSET
        else:
            mtls_security_scheme = MutualTlsSecurityScheme.from_dict(_mtls_security_scheme)




        agent_card_security_scheme = cls(
            api_key_security_scheme=api_key_security_scheme,
            http_auth_security_scheme=http_auth_security_scheme,
            oauth_2_security_scheme=oauth_2_security_scheme,
            open_id_connect_security_scheme=open_id_connect_security_scheme,
            mtls_security_scheme=mtls_security_scheme,
        )


        agent_card_security_scheme.additional_properties = d
        return agent_card_security_scheme

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
