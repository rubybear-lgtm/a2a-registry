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
  from ..models.security_requirement import SecurityRequirement





T = TypeVar("T", bound="AgentCardSkill")



@_attrs_define
class AgentCardSkill:
    """ 
        Attributes:
            id (str):
            name (str):
            description (str):
            tags (list[str]):
            examples (Union[None, Unset, list[str]]):
            input_modes (Union[None, Unset, list[str]]):
            output_modes (Union[None, Unset, list[str]]):
            security_requirements (Union[None, Unset, list['SecurityRequirement']]):
     """

    id: str
    name: str
    description: str
    tags: list[str]
    examples: Union[None, Unset, list[str]] = UNSET
    input_modes: Union[None, Unset, list[str]] = UNSET
    output_modes: Union[None, Unset, list[str]] = UNSET
    security_requirements: Union[None, Unset, list['SecurityRequirement']] = UNSET
    additional_properties: dict[str, Any] = _attrs_field(init=False, factory=dict)





    def to_dict(self) -> dict[str, Any]:
        from ..models.security_requirement import SecurityRequirement
        id = self.id

        name = self.name

        description = self.description

        tags = self.tags



        examples: Union[None, Unset, list[str]]
        if isinstance(self.examples, Unset):
            examples = UNSET
        elif isinstance(self.examples, list):
            examples = self.examples


        else:
            examples = self.examples

        input_modes: Union[None, Unset, list[str]]
        if isinstance(self.input_modes, Unset):
            input_modes = UNSET
        elif isinstance(self.input_modes, list):
            input_modes = self.input_modes


        else:
            input_modes = self.input_modes

        output_modes: Union[None, Unset, list[str]]
        if isinstance(self.output_modes, Unset):
            output_modes = UNSET
        elif isinstance(self.output_modes, list):
            output_modes = self.output_modes


        else:
            output_modes = self.output_modes

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


        field_dict: dict[str, Any] = {}
        field_dict.update(self.additional_properties)
        field_dict.update({
            "id": id,
            "name": name,
            "description": description,
            "tags": tags,
        })
        if examples is not UNSET:
            field_dict["examples"] = examples
        if input_modes is not UNSET:
            field_dict["inputModes"] = input_modes
        if output_modes is not UNSET:
            field_dict["outputModes"] = output_modes
        if security_requirements is not UNSET:
            field_dict["securityRequirements"] = security_requirements

        return field_dict



    @classmethod
    def from_dict(cls: type[T], src_dict: Mapping[str, Any]) -> T:
        from ..models.security_requirement import SecurityRequirement
        d = dict(src_dict)
        id = d.pop("id")

        name = d.pop("name")

        description = d.pop("description")

        tags = cast(list[str], d.pop("tags"))


        def _parse_examples(data: object) -> Union[None, Unset, list[str]]:
            if data is None:
                return data
            if isinstance(data, Unset):
                return data
            try:
                if not isinstance(data, list):
                    raise TypeError()
                examples_type_0 = cast(list[str], data)

                return examples_type_0
            except: # noqa: E722
                pass
            return cast(Union[None, Unset, list[str]], data)

        examples = _parse_examples(d.pop("examples", UNSET))


        def _parse_input_modes(data: object) -> Union[None, Unset, list[str]]:
            if data is None:
                return data
            if isinstance(data, Unset):
                return data
            try:
                if not isinstance(data, list):
                    raise TypeError()
                input_modes_type_0 = cast(list[str], data)

                return input_modes_type_0
            except: # noqa: E722
                pass
            return cast(Union[None, Unset, list[str]], data)

        input_modes = _parse_input_modes(d.pop("inputModes", UNSET))


        def _parse_output_modes(data: object) -> Union[None, Unset, list[str]]:
            if data is None:
                return data
            if isinstance(data, Unset):
                return data
            try:
                if not isinstance(data, list):
                    raise TypeError()
                output_modes_type_0 = cast(list[str], data)

                return output_modes_type_0
            except: # noqa: E722
                pass
            return cast(Union[None, Unset, list[str]], data)

        output_modes = _parse_output_modes(d.pop("outputModes", UNSET))


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


        agent_card_skill = cls(
            id=id,
            name=name,
            description=description,
            tags=tags,
            examples=examples,
            input_modes=input_modes,
            output_modes=output_modes,
            security_requirements=security_requirements,
        )


        agent_card_skill.additional_properties = d
        return agent_card_skill

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
