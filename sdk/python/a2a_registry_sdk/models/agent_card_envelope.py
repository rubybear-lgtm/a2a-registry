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
  from ..models.agent_card_v1 import AgentCardV1
  from ..models.agent_card_envelope_validation_warnings import AgentCardEnvelopeValidationWarnings





T = TypeVar("T", bound="AgentCardEnvelope")



@_attrs_define
class AgentCardEnvelope:
    """ 
        Attributes:
            id (str):
            source_url (str):
            status (AgentListingStatus):
            card (AgentCardV1):
            validation_warnings (AgentCardEnvelopeValidationWarnings):
            etag (Union[None, str]):
            last_modified (Union[None, str]):
            content_hash (Union[None, str]):
            fetched_at (Union[None, datetime.datetime]):
            validated_at (Union[None, datetime.datetime]):
     """

    id: str
    source_url: str
    status: AgentListingStatus
    card: 'AgentCardV1'
    validation_warnings: 'AgentCardEnvelopeValidationWarnings'
    etag: Union[None, str]
    last_modified: Union[None, str]
    content_hash: Union[None, str]
    fetched_at: Union[None, datetime.datetime]
    validated_at: Union[None, datetime.datetime]
    additional_properties: dict[str, Any] = _attrs_field(init=False, factory=dict)





    def to_dict(self) -> dict[str, Any]:
        from ..models.agent_card_v1 import AgentCardV1
        from ..models.agent_card_envelope_validation_warnings import AgentCardEnvelopeValidationWarnings
        id = self.id

        source_url = self.source_url

        status = self.status.value

        card = self.card.to_dict()

        validation_warnings = self.validation_warnings.to_dict()

        etag: Union[None, str]
        etag = self.etag

        last_modified: Union[None, str]
        last_modified = self.last_modified

        content_hash: Union[None, str]
        content_hash = self.content_hash

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
            "source_url": source_url,
            "status": status,
            "card": card,
            "validation_warnings": validation_warnings,
            "etag": etag,
            "last_modified": last_modified,
            "content_hash": content_hash,
            "fetched_at": fetched_at,
            "validated_at": validated_at,
        })

        return field_dict



    @classmethod
    def from_dict(cls: type[T], src_dict: Mapping[str, Any]) -> T:
        from ..models.agent_card_v1 import AgentCardV1
        from ..models.agent_card_envelope_validation_warnings import AgentCardEnvelopeValidationWarnings
        d = dict(src_dict)
        id = d.pop("id")

        source_url = d.pop("source_url")

        status = AgentListingStatus(d.pop("status"))




        card = AgentCardV1.from_dict(d.pop("card"))




        validation_warnings = AgentCardEnvelopeValidationWarnings.from_dict(d.pop("validation_warnings"))




        def _parse_etag(data: object) -> Union[None, str]:
            if data is None:
                return data
            return cast(Union[None, str], data)

        etag = _parse_etag(d.pop("etag"))


        def _parse_last_modified(data: object) -> Union[None, str]:
            if data is None:
                return data
            return cast(Union[None, str], data)

        last_modified = _parse_last_modified(d.pop("last_modified"))


        def _parse_content_hash(data: object) -> Union[None, str]:
            if data is None:
                return data
            return cast(Union[None, str], data)

        content_hash = _parse_content_hash(d.pop("content_hash"))


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


        agent_card_envelope = cls(
            id=id,
            source_url=source_url,
            status=status,
            card=card,
            validation_warnings=validation_warnings,
            etag=etag,
            last_modified=last_modified,
            content_hash=content_hash,
            fetched_at=fetched_at,
            validated_at=validated_at,
        )


        agent_card_envelope.additional_properties = d
        return agent_card_envelope

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
