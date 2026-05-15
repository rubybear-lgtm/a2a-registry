from collections.abc import Mapping
from typing import Any, TypeVar, Optional, BinaryIO, TextIO, TYPE_CHECKING, Generator

from attrs import define as _attrs_define
from attrs import field as _attrs_field

from ..types import UNSET, Unset

from ..types import UNSET, Unset
from typing import cast
from typing import cast, Union
from typing import Union

if TYPE_CHECKING:
  from ..models.agent_card_supported_interface import AgentCardSupportedInterface
  from ..models.agent_card_v1_signatures_type_0 import AgentCardV1SignaturesType0
  from ..models.agent_card_skill import AgentCardSkill
  from ..models.agent_card_provider import AgentCardProvider
  from ..models.security_requirement import SecurityRequirement
  from ..models.agent_card_capabilities import AgentCardCapabilities
  from ..models.agent_card_v1_security_schemes_type_0 import AgentCardV1SecuritySchemesType0





T = TypeVar("T", bound="AgentCardV1")



@_attrs_define
class AgentCardV1:
    """ 
        Attributes:
            name (str):
            description (str):
            supported_interfaces (list['AgentCardSupportedInterface']):
            version (str):
            capabilities (AgentCardCapabilities):
            default_input_modes (list[str]):
            default_output_modes (list[str]):
            skills (list['AgentCardSkill']):
            provider (Union[Unset, AgentCardProvider]):
            documentation_url (Union[None, Unset, str]):
            icon_url (Union[None, Unset, str]):
            security_schemes (Union['AgentCardV1SecuritySchemesType0', None, Unset]):
            security_requirements (Union[None, Unset, list['SecurityRequirement']]):
            signatures (Union['AgentCardV1SignaturesType0', None, Unset]):
     """

    name: str
    description: str
    supported_interfaces: list['AgentCardSupportedInterface']
    version: str
    capabilities: 'AgentCardCapabilities'
    default_input_modes: list[str]
    default_output_modes: list[str]
    skills: list['AgentCardSkill']
    provider: Union[Unset, 'AgentCardProvider'] = UNSET
    documentation_url: Union[None, Unset, str] = UNSET
    icon_url: Union[None, Unset, str] = UNSET
    security_schemes: Union['AgentCardV1SecuritySchemesType0', None, Unset] = UNSET
    security_requirements: Union[None, Unset, list['SecurityRequirement']] = UNSET
    signatures: Union['AgentCardV1SignaturesType0', None, Unset] = UNSET
    additional_properties: dict[str, Any] = _attrs_field(init=False, factory=dict)





    def to_dict(self) -> dict[str, Any]:
        from ..models.agent_card_supported_interface import AgentCardSupportedInterface
        from ..models.agent_card_v1_signatures_type_0 import AgentCardV1SignaturesType0
        from ..models.agent_card_skill import AgentCardSkill
        from ..models.agent_card_provider import AgentCardProvider
        from ..models.security_requirement import SecurityRequirement
        from ..models.agent_card_capabilities import AgentCardCapabilities
        from ..models.agent_card_v1_security_schemes_type_0 import AgentCardV1SecuritySchemesType0
        name = self.name

        description = self.description

        supported_interfaces = []
        for supported_interfaces_item_data in self.supported_interfaces:
            supported_interfaces_item = supported_interfaces_item_data.to_dict()
            supported_interfaces.append(supported_interfaces_item)



        version = self.version

        capabilities = self.capabilities.to_dict()

        default_input_modes = self.default_input_modes



        default_output_modes = self.default_output_modes



        skills = []
        for skills_item_data in self.skills:
            skills_item = skills_item_data.to_dict()
            skills.append(skills_item)



        provider: Union[Unset, dict[str, Any]] = UNSET
        if not isinstance(self.provider, Unset):
            provider = self.provider.to_dict()

        documentation_url: Union[None, Unset, str]
        if isinstance(self.documentation_url, Unset):
            documentation_url = UNSET
        else:
            documentation_url = self.documentation_url

        icon_url: Union[None, Unset, str]
        if isinstance(self.icon_url, Unset):
            icon_url = UNSET
        else:
            icon_url = self.icon_url

        security_schemes: Union[None, Unset, dict[str, Any]]
        if isinstance(self.security_schemes, Unset):
            security_schemes = UNSET
        elif isinstance(self.security_schemes, AgentCardV1SecuritySchemesType0):
            security_schemes = self.security_schemes.to_dict()
        else:
            security_schemes = self.security_schemes

        security_requirements: Union[None, Unset, list[dict[str, Any]]]
        if isinstance(self.security_requirements, Unset):
            security_requirements = UNSET
        elif isinstance(self.security_requirements, list):
            security_requirements = []
            for security_requirements_type_0_item_data in self.security_requirements:
                security_requirements_type_0_item = security_requirements_type_0_item_data.to_dict()
                security_requirements.append(security_requirements_type_0_item)


        else:
            security_requirements = self.security_requirements

        signatures: Union[None, Unset, dict[str, Any]]
        if isinstance(self.signatures, Unset):
            signatures = UNSET
        elif isinstance(self.signatures, AgentCardV1SignaturesType0):
            signatures = self.signatures.to_dict()
        else:
            signatures = self.signatures


        field_dict: dict[str, Any] = {}
        field_dict.update(self.additional_properties)
        field_dict.update({
            "name": name,
            "description": description,
            "supportedInterfaces": supported_interfaces,
            "version": version,
            "capabilities": capabilities,
            "defaultInputModes": default_input_modes,
            "defaultOutputModes": default_output_modes,
            "skills": skills,
        })
        if provider is not UNSET:
            field_dict["provider"] = provider
        if documentation_url is not UNSET:
            field_dict["documentationUrl"] = documentation_url
        if icon_url is not UNSET:
            field_dict["iconUrl"] = icon_url
        if security_schemes is not UNSET:
            field_dict["securitySchemes"] = security_schemes
        if security_requirements is not UNSET:
            field_dict["securityRequirements"] = security_requirements
        if signatures is not UNSET:
            field_dict["signatures"] = signatures

        return field_dict



    @classmethod
    def from_dict(cls: type[T], src_dict: Mapping[str, Any]) -> T:
        from ..models.agent_card_supported_interface import AgentCardSupportedInterface
        from ..models.agent_card_v1_signatures_type_0 import AgentCardV1SignaturesType0
        from ..models.agent_card_skill import AgentCardSkill
        from ..models.agent_card_provider import AgentCardProvider
        from ..models.security_requirement import SecurityRequirement
        from ..models.agent_card_capabilities import AgentCardCapabilities
        from ..models.agent_card_v1_security_schemes_type_0 import AgentCardV1SecuritySchemesType0
        d = dict(src_dict)
        name = d.pop("name")

        description = d.pop("description")

        supported_interfaces = []
        _supported_interfaces = d.pop("supportedInterfaces")
        for supported_interfaces_item_data in (_supported_interfaces):
            supported_interfaces_item = AgentCardSupportedInterface.from_dict(supported_interfaces_item_data)



            supported_interfaces.append(supported_interfaces_item)


        version = d.pop("version")

        capabilities = AgentCardCapabilities.from_dict(d.pop("capabilities"))




        default_input_modes = cast(list[str], d.pop("defaultInputModes"))


        default_output_modes = cast(list[str], d.pop("defaultOutputModes"))


        skills = []
        _skills = d.pop("skills")
        for skills_item_data in (_skills):
            skills_item = AgentCardSkill.from_dict(skills_item_data)



            skills.append(skills_item)


        _provider = d.pop("provider", UNSET)
        provider: Union[Unset, AgentCardProvider]
        if isinstance(_provider,  Unset):
            provider = UNSET
        else:
            provider = AgentCardProvider.from_dict(_provider)




        def _parse_documentation_url(data: object) -> Union[None, Unset, str]:
            if data is None:
                return data
            if isinstance(data, Unset):
                return data
            return cast(Union[None, Unset, str], data)

        documentation_url = _parse_documentation_url(d.pop("documentationUrl", UNSET))


        def _parse_icon_url(data: object) -> Union[None, Unset, str]:
            if data is None:
                return data
            if isinstance(data, Unset):
                return data
            return cast(Union[None, Unset, str], data)

        icon_url = _parse_icon_url(d.pop("iconUrl", UNSET))


        def _parse_security_schemes(data: object) -> Union['AgentCardV1SecuritySchemesType0', None, Unset]:
            if data is None:
                return data
            if isinstance(data, Unset):
                return data
            try:
                if not isinstance(data, dict):
                    raise TypeError()
                security_schemes_type_0 = AgentCardV1SecuritySchemesType0.from_dict(data)



                return security_schemes_type_0
            except: # noqa: E722
                pass
            return cast(Union['AgentCardV1SecuritySchemesType0', None, Unset], data)

        security_schemes = _parse_security_schemes(d.pop("securitySchemes", UNSET))


        def _parse_security_requirements(data: object) -> Union[None, Unset, list['SecurityRequirement']]:
            if data is None:
                return data
            if isinstance(data, Unset):
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
            return cast(Union[None, Unset, list['SecurityRequirement']], data)

        security_requirements = _parse_security_requirements(d.pop("securityRequirements", UNSET))


        def _parse_signatures(data: object) -> Union['AgentCardV1SignaturesType0', None, Unset]:
            if data is None:
                return data
            if isinstance(data, Unset):
                return data
            try:
                if not isinstance(data, dict):
                    raise TypeError()
                signatures_type_0 = AgentCardV1SignaturesType0.from_dict(data)



                return signatures_type_0
            except: # noqa: E722
                pass
            return cast(Union['AgentCardV1SignaturesType0', None, Unset], data)

        signatures = _parse_signatures(d.pop("signatures", UNSET))


        agent_card_v1 = cls(
            name=name,
            description=description,
            supported_interfaces=supported_interfaces,
            version=version,
            capabilities=capabilities,
            default_input_modes=default_input_modes,
            default_output_modes=default_output_modes,
            skills=skills,
            provider=provider,
            documentation_url=documentation_url,
            icon_url=icon_url,
            security_schemes=security_schemes,
            security_requirements=security_requirements,
            signatures=signatures,
        )


        agent_card_v1.additional_properties = d
        return agent_card_v1

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
