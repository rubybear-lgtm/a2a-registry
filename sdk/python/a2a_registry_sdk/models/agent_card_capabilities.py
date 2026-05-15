from collections.abc import Mapping
from typing import Any, TypeVar, Optional, BinaryIO, TextIO, TYPE_CHECKING, Generator

from attrs import define as _attrs_define
from attrs import field as _attrs_field

from ..types import UNSET, Unset







T = TypeVar("T", bound="AgentCardCapabilities")



@_attrs_define
class AgentCardCapabilities:
    """ 
        Attributes:
            streaming (bool):
            push_notifications (bool):
            extended_agent_card (bool):
     """

    streaming: bool
    push_notifications: bool
    extended_agent_card: bool
    additional_properties: dict[str, Any] = _attrs_field(init=False, factory=dict)





    def to_dict(self) -> dict[str, Any]:
        streaming = self.streaming

        push_notifications = self.push_notifications

        extended_agent_card = self.extended_agent_card


        field_dict: dict[str, Any] = {}
        field_dict.update(self.additional_properties)
        field_dict.update({
            "streaming": streaming,
            "pushNotifications": push_notifications,
            "extendedAgentCard": extended_agent_card,
        })

        return field_dict



    @classmethod
    def from_dict(cls: type[T], src_dict: Mapping[str, Any]) -> T:
        d = dict(src_dict)
        streaming = d.pop("streaming")

        push_notifications = d.pop("pushNotifications")

        extended_agent_card = d.pop("extendedAgentCard")

        agent_card_capabilities = cls(
            streaming=streaming,
            push_notifications=push_notifications,
            extended_agent_card=extended_agent_card,
        )


        agent_card_capabilities.additional_properties = d
        return agent_card_capabilities

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
