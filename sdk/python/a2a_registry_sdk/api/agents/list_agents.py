from http import HTTPStatus
from typing import Any, Optional, Union, cast

import httpx

from ...client import AuthenticatedClient, Client
from ...types import Response, UNSET
from ... import errors

from ...models.agent_listing_status import AgentListingStatus
from ...models.paginated_agent_listing_response import PaginatedAgentListingResponse
from ...models.validation_error_response import ValidationErrorResponse
from ...types import UNSET, Unset
from typing import cast
from typing import Union



def _get_kwargs(
    *,
    q: Union[Unset, str] = UNSET,
    status: Union[Unset, AgentListingStatus] = UNSET,
    page: Union[Unset, int] = UNSET,

) -> dict[str, Any]:
    

    

    params: dict[str, Any] = {}

    params["q"] = q

    json_status: Union[Unset, str] = UNSET
    if not isinstance(status, Unset):
        json_status = status.value

    params["status"] = json_status

    params["page"] = page


    params = {k: v for k, v in params.items() if v is not UNSET and v is not None}


    _kwargs: dict[str, Any] = {
        "method": "get",
        "url": "/api/v1/agents",
        "params": params,
    }


    return _kwargs



def _parse_response(*, client: Union[AuthenticatedClient, Client], response: httpx.Response) -> Optional[Union[PaginatedAgentListingResponse, ValidationErrorResponse]]:
    if response.status_code == 200:
        response_200 = PaginatedAgentListingResponse.from_dict(response.json())



        return response_200

    if response.status_code == 422:
        response_422 = ValidationErrorResponse.from_dict(response.json())



        return response_422

    if client.raise_on_unexpected_status:
        raise errors.UnexpectedStatus(response.status_code, response.content)
    else:
        return None


def _build_response(*, client: Union[AuthenticatedClient, Client], response: httpx.Response) -> Response[Union[PaginatedAgentListingResponse, ValidationErrorResponse]]:
    return Response(
        status_code=HTTPStatus(response.status_code),
        content=response.content,
        headers=response.headers,
        parsed=_parse_response(client=client, response=response),
    )


def sync_detailed(
    *,
    client: Union[AuthenticatedClient, Client],
    q: Union[Unset, str] = UNSET,
    status: Union[Unset, AgentListingStatus] = UNSET,
    page: Union[Unset, int] = UNSET,

) -> Response[Union[PaginatedAgentListingResponse, ValidationErrorResponse]]:
    """ List visible agent listings

     Returns a paginated collection of publicly visible agent listings.

    Args:
        q (Union[Unset, str]):
        status (Union[Unset, AgentListingStatus]):
        page (Union[Unset, int]):

    Raises:
        errors.UnexpectedStatus: If the server returns an undocumented status code and Client.raise_on_unexpected_status is True.
        httpx.TimeoutException: If the request takes longer than Client.timeout.

    Returns:
        Response[Union[PaginatedAgentListingResponse, ValidationErrorResponse]]
     """


    kwargs = _get_kwargs(
        q=q,
status=status,
page=page,

    )

    response = client.get_httpx_client().request(
        **kwargs,
    )

    return _build_response(client=client, response=response)

def sync(
    *,
    client: Union[AuthenticatedClient, Client],
    q: Union[Unset, str] = UNSET,
    status: Union[Unset, AgentListingStatus] = UNSET,
    page: Union[Unset, int] = UNSET,

) -> Optional[Union[PaginatedAgentListingResponse, ValidationErrorResponse]]:
    """ List visible agent listings

     Returns a paginated collection of publicly visible agent listings.

    Args:
        q (Union[Unset, str]):
        status (Union[Unset, AgentListingStatus]):
        page (Union[Unset, int]):

    Raises:
        errors.UnexpectedStatus: If the server returns an undocumented status code and Client.raise_on_unexpected_status is True.
        httpx.TimeoutException: If the request takes longer than Client.timeout.

    Returns:
        Union[PaginatedAgentListingResponse, ValidationErrorResponse]
     """


    return sync_detailed(
        client=client,
q=q,
status=status,
page=page,

    ).parsed

async def asyncio_detailed(
    *,
    client: Union[AuthenticatedClient, Client],
    q: Union[Unset, str] = UNSET,
    status: Union[Unset, AgentListingStatus] = UNSET,
    page: Union[Unset, int] = UNSET,

) -> Response[Union[PaginatedAgentListingResponse, ValidationErrorResponse]]:
    """ List visible agent listings

     Returns a paginated collection of publicly visible agent listings.

    Args:
        q (Union[Unset, str]):
        status (Union[Unset, AgentListingStatus]):
        page (Union[Unset, int]):

    Raises:
        errors.UnexpectedStatus: If the server returns an undocumented status code and Client.raise_on_unexpected_status is True.
        httpx.TimeoutException: If the request takes longer than Client.timeout.

    Returns:
        Response[Union[PaginatedAgentListingResponse, ValidationErrorResponse]]
     """


    kwargs = _get_kwargs(
        q=q,
status=status,
page=page,

    )

    response = await client.get_async_httpx_client().request(
        **kwargs
    )

    return _build_response(client=client, response=response)

async def asyncio(
    *,
    client: Union[AuthenticatedClient, Client],
    q: Union[Unset, str] = UNSET,
    status: Union[Unset, AgentListingStatus] = UNSET,
    page: Union[Unset, int] = UNSET,

) -> Optional[Union[PaginatedAgentListingResponse, ValidationErrorResponse]]:
    """ List visible agent listings

     Returns a paginated collection of publicly visible agent listings.

    Args:
        q (Union[Unset, str]):
        status (Union[Unset, AgentListingStatus]):
        page (Union[Unset, int]):

    Raises:
        errors.UnexpectedStatus: If the server returns an undocumented status code and Client.raise_on_unexpected_status is True.
        httpx.TimeoutException: If the request takes longer than Client.timeout.

    Returns:
        Union[PaginatedAgentListingResponse, ValidationErrorResponse]
     """


    return (await asyncio_detailed(
        client=client,
q=q,
status=status,
page=page,

    )).parsed
