from collections.abc import Mapping
from typing import Any, TypeVar, Optional, BinaryIO, TextIO, TYPE_CHECKING, Generator

from attrs import define as _attrs_define
from attrs import field as _attrs_field

from ..types import UNSET, Unset

from ..models.agent_listing_status import AgentListingStatus
from dateutil.parser import isoparse
from typing import cast
from typing import cast, Union
import datetime

if TYPE_CHECKING:
  from ..models.agent_card_skill import AgentCardSkill
  from ..models.agent_card_supported_interface import AgentCardSupportedInterface
  from ..models.security_requirement import SecurityRequirement





T = TypeVar("T", bound="AgentListing")



@_attrs_define
class AgentListing:
    """ 
        Attributes:
            id (str):
            name (str):
            description (Union[None, str]):
            provider_name (Union[None, str]):
            agent_version (str):
            documentation_url (Union[None, str]):
            icon_url (Union[None, str]):
            preferred_interface_url (str):
            preferred_protocol_binding (str):
            preferred_protocol_version (str):
            status (AgentListingStatus):
            has_auth (bool):
            supports_streaming (bool):
            supports_push_notifications (bool):
            supports_extended_agent_card (bool):
            default_input_modes (list[str]):
            default_output_modes (list[str]):
            supported_interfaces (list['AgentCardSupportedInterface']):
            security_requirements (Union[None, list['SecurityRequirement']]):
            skills (list['AgentCardSkill']):
            source_url (str):
            card_url (str):
            fetched_at (Union[None, datetime.datetime]):
            validated_at (Union[None, datetime.datetime]):
     """

    id: str
    name: str
    description: Union[None, str]
    provider_name: Union[None, str]
    agent_version: str
    documentation_url: Union[None, str]
    icon_url: Union[None, str]
    preferred_interface_url: str
    preferred_protocol_binding: str
    preferred_protocol_version: str
    status: AgentListingStatus
    has_auth: bool
    supports_streaming: bool
    supports_push_notifications: bool
    supports_extended_agent_card: bool
    default_input_modes: list[str]
    default_output_modes: list[str]
    supported_interfaces: list['AgentCardSupportedInterface']
    security_requirements: Union[None, list['SecurityRequirement']]
    skills: list['AgentCardSkill']
    source_url: str
    card_url: str
    fetched_at: Union[None, datetime.datetime]
    validated_at: Union[None, datetime.datetime]
    additional_properties: dict[str, Any] = _attrs_field(init=False, factory=dict)





    def to_dict(self) -> dict[str, Any]:
        from ..models.agent_card_skill import AgentCardSkill
        from ..models.agent_card_supported_interface import AgentCardSupportedInterface
        from ..models.security_requirement import SecurityRequirement
        id = self.id

        name = self.name

        description: Union[None, str]
        description = self.description

        provider_name: Union[None, str]
        provider_name = self.provider_name

        agent_version = self.agent_version

        documentation_url: Union[None, str]
        documentation_url = self.documentation_url

        icon_url: Union[None, str]
        icon_url = self.icon_url

        preferred_interface_url = self.preferred_interface_url

        preferred_protocol_binding = self.preferred_protocol_binding

        preferred_protocol_version = self.preferred_protocol_version

        status = self.status.value

        has_auth = self.has_auth

        supports_streaming = self.supports_streaming

        supports_push_notifications = self.supports_push_notifications

        supports_extended_agent_card = self.supports_extended_agent_card

        default_input_modes = self.default_input_modes



        default_output_modes = self.default_output_modes



        supported_interfaces = []
        for supported_interfaces_item_data in self.supported_interfaces:
            supported_interfaces_item = supported_interfaces_item_data.to_dict()
            supported_interfaces.append(supported_interfaces_item)



        security_requirements: Union[None, list[dict[str, Any]]]
        if isinstance(self.security_requirements, list):
            security_requirements = []
            for security_requirements_type_0_item_data in self.security_requirements:
                security_requirements_type_0_item = security_requirements_type_0_item_data.to_dict()
                security_requirements.append(security_requirements_type_0_item)


        else:
            security_requirements = self.security_requirements

        skills = []
        for skills_item_data in self.skills:
            skills_item = skills_item_data.to_dict()
            skills.append(skills_item)



        source_url = self.source_url

        card_url = self.card_url

        fetched_at: Union[None, str]
        if isinstance(self.fetched_at, datetime.datetime):
            fetched_at = self.fetched_at.isoformat()
        else:
            fetched_at = self.fetched_at

        validated_at: Union[None, str]
        if isinstance(self.validated_at, datetime.datetime):
            validated_at = self.validated_at.isoformat()
        else:
            validated_at = self.validated_at


        field_dict: dict[str, Any] = {}
        field_dict.update(self.additional_properties)
        field_dict.update({
            "id": id,
            "name": name,
            "description": description,
            "provider_name": provider_name,
            "agent_version": agent_version,
            "documentation_url": documentation_url,
            "icon_url": icon_url,
            "preferred_interface_url": preferred_interface_url,
            "preferred_protocol_binding": preferred_protocol_binding,
            "preferred_protocol_version": preferred_protocol_version,
            "status": status,
            "has_auth": has_auth,
            "supports_streaming": supports_streaming,
            "supports_push_notifications": supports_push_notifications,
            "supports_extended_agent_card": supports_extended_agent_card,
            "default_input_modes": default_input_modes,
            "default_output_modes": default_output_modes,
            "supported_interfaces": supported_interfaces,
            "security_requirements": security_requirements,
            "skills": skills,
            "source_url": source_url,
            "card_url": card_url,
            "fetched_at": fetched_at,
            "validated_at": validated_at,
        })

        return field_dict



    @classmethod
    def from_dict(cls: type[T], src_dict: Mapping[str, Any]) -> T:
        from ..models.agent_card_skill import AgentCardSkill
        from ..models.agent_card_supported_interface import AgentCardSupportedInterface
        from ..models.security_requirement import SecurityRequirement
        d = dict(src_dict)
        id = d.pop("id")

        name = d.pop("name")

        def _parse_description(data: object) -> Union[None, str]:
            if data is None:
                return data
            return cast(Union[None, str], data)

        description = _parse_description(d.pop("description"))


        def _parse_provider_name(data: object) -> Union[None, str]:
            if data is None:
                return data
            return cast(Union[None, str], data)

        provider_name = _parse_provider_name(d.pop("provider_name"))


        agent_version = d.pop("agent_version")

        def _parse_documentation_url(data: object) -> Union[None, str]:
            if data is None:
                return data
            return cast(Union[None, str], data)

        documentation_url = _parse_documentation_url(d.pop("documentation_url"))


        def _parse_icon_url(data: object) -> Union[None, str]:
            if data is None:
                return data
            return cast(Union[None, str], data)

        icon_url = _parse_icon_url(d.pop("icon_url"))


        preferred_interface_url = d.pop("preferred_interface_url")

        preferred_protocol_binding = d.pop("preferred_protocol_binding")

        preferred_protocol_version = d.pop("preferred_protocol_version")

        status = AgentListingStatus(d.pop("status"))




        has_auth = d.pop("has_auth")

        supports_streaming = d.pop("supports_streaming")

        supports_push_notifications = d.pop("supports_push_notifications")

        supports_extended_agent_card = d.pop("supports_extended_agent_card")

        default_input_modes = cast(list[str], d.pop("default_input_modes"))


        default_output_modes = cast(list[str], d.pop("default_output_modes"))


        supported_interfaces = []
        _supported_interfaces = d.pop("supported_interfaces")
        for supported_interfaces_item_data in (_supported_interfaces):
            supported_interfaces_item = AgentCardSupportedInterface.from_dict(supported_interfaces_item_data)



            supported_interfaces.append(supported_interfaces_item)


        def _parse_security_requirements(data: object) -> Union[None, list['SecurityRequirement']]:
            if data is None:
                return data
            try:
                if not isinstance(data, list):
                    raise TypeError()
                security_requirements_type_0 = []
                _security_requirements_type_0 = data
                for security_requirements_type_0_item_data in (_security_requirements_type_0):
                    security_requirements_type_0_item = SecurityRequirement.from_dict(security_requirements_type_0_item_data)



                    security_requirements_type_0.append(security_requirements_type_0_item)

                return security_requirements_type_0
            except: # noqa: E722
                pass
            return cast(Union[None, list['SecurityRequirement']], data)

        security_requirements = _parse_security_requirements(d.pop("security_requirements"))


        skills = []
        _skills = d.pop("skills")
        for skills_item_data in (_skills):
            skills_item = AgentCardSkill.from_dict(skills_item_data)



            skills.append(skills_item)


        source_url = d.pop("source_url")

        card_url = d.pop("card_url")

        def _parse_fetched_at(data: object) -> Union[None, datetime.datetime]:
            if data is None:
                return data
            try:
                if not isinstance(data, str):
                    raise TypeError()
                fetched_at_type_0 = isoparse(data)



                return fetched_at_type_0
            except: # noqa: E722
                pass
            return cast(Union[None, datetime.datetime], data)

        fetched_at = _parse_fetched_at(d.pop("fetched_at"))


        def _parse_validated_at(data: object) -> Union[None, datetime.datetime]:
            if data is None:
                return data
            try:
                if not isinstance(data, str):
                    raise TypeError()
                validated_at_type_0 = isoparse(data)



                return validated_at_type_0
            except: # noqa: E722
                pass
            return cast(Union[None, datetime.datetime], data)

        validated_at = _parse_validated_at(d.pop("validated_at"))


        agent_listing = cls(
            id=id,
            name=name,
            description=description,
            provider_name=provider_name,
            agent_version=agent_version,
            documentation_url=documentation_url,
            icon_url=icon_url,
            preferred_interface_url=preferred_interface_url,
            preferred_protocol_binding=preferred_protocol_binding,
            preferred_protocol_version=preferred_protocol_version,
            status=status,
            has_auth=has_auth,
            supports_streaming=supports_streaming,
            supports_push_notifications=supports_push_notifications,
            supports_extended_agent_card=supports_extended_agent_card,
            default_input_modes=default_input_modes,
            default_output_modes=default_output_modes,
            supported_interfaces=supported_interfaces,
            security_requirements=security_requirements,
            skills=skills,
            source_url=source_url,
            card_url=card_url,
            fetched_at=fetched_at,
            validated_at=validated_at,
        )


        agent_listing.additional_properties = d
        return agent_listing

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
