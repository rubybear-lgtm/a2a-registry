from collections.abc import Mapping
from typing import Any, TypeVar, Optional, BinaryIO, TextIO, TYPE_CHECKING, Generator

from attrs import define as _attrs_define
from attrs import field as _attrs_field

from ..types import UNSET, Unset

from typing import cast

if TYPE_CHECKING:
  from ..models.agent_listing import AgentListing
  from ..models.pagination_links import PaginationLinks
  from ..models.pagination_meta import PaginationMeta





T = TypeVar("T", bound="PaginatedAgentListingResponse")



@_attrs_define
class PaginatedAgentListingResponse:
    """ 
        Attributes:
            data (list['AgentListing']):
            links (PaginationLinks):
            meta (PaginationMeta):
     """

    data: list['AgentListing']
    links: 'PaginationLinks'
    meta: 'PaginationMeta'
    additional_properties: dict[str, Any] = _attrs_field(init=False, factory=dict)





    def to_dict(self) -> dict[str, Any]:
        from ..models.agent_listing import AgentListing
        from ..models.pagination_links import PaginationLinks
        from ..models.pagination_meta import PaginationMeta
        data = []
        for data_item_data in self.data:
            data_item = data_item_data.to_dict()
            data.append(data_item)



        links = self.links.to_dict()

        meta = self.meta.to_dict()


        field_dict: dict[str, Any] = {}
        field_dict.update(self.additional_properties)
        field_dict.update({
            "data": data,
            "links": links,
            "meta": meta,
        })

        return field_dict



    @classmethod
    def from_dict(cls: type[T], src_dict: Mapping[str, Any]) -> T:
        from ..models.agent_listing import AgentListing
        from ..models.pagination_links import PaginationLinks
        from ..models.pagination_meta import PaginationMeta
        d = dict(src_dict)
        data = []
        _data = d.pop("data")
        for data_item_data in (_data):
            data_item = AgentListing.from_dict(data_item_data)



            data.append(data_item)


        links = PaginationLinks.from_dict(d.pop("links"))




        meta = PaginationMeta.from_dict(d.pop("meta"))




        paginated_agent_listing_response = cls(
            data=data,
            links=links,
            meta=meta,
        )


        paginated_agent_listing_response.additional_properties = d
        return paginated_agent_listing_response

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
