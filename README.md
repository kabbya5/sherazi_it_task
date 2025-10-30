### Database Configuration

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sherazi_it_task
DB_USERNAME=root
DB_PASSWORD=


## API Endpoint: `api/transactions`

This endpoint allows you to fetch transactions filtered by account.

### Request

**Method:** `GET`  
**URL:** `/api/transactions`  
**Headers:**
```http
Content-Type: application/json
Accept: application/json

{
  "account_id": 2
}

{
  "status": 1,
  "message": "Transactions fetched successfully",
  "data": [
    {
      "id": 101,
      "account_id": 2,
      "type": "debit",
      "amount": 500.00,
      "date": "2025-10-30",
      "description": "Payment received"
    },
    {
      "id": 102,
      "account_id": 2,
      "type": "credit",
      "amount": 200.00,
      "date": "2025-10-30",
      "description": "Invoice paid"
    }
  ]
}
```
##3 Error Response:
```
Code: 400 / 404 / 500
{
  "status": 0,
  "message": "Failed to fetch transactions.",
  "error": "Account not found or server error"
}
```
## Ledger Report
**Method:** `GET`  
**URL:** `/api/ledger/report/1`  

### **Response Example (Success)**
```json
{
    "status": 1,
    "transactions": [
        {
            "account_id": 1,
            "account_name": "Cash",
            "account_code": "AC-001",
            "balance": "-6066833.00",
            "total_debit": "5976648.00",
            "total_credit": "11509457.00"
        }
    ]
}
```
### Error Response Example
```
{
    "status": 0,
    "message": "Failed to fetch transactions."
}
```
**Method:** `POST`  
**URL:** `/api/transactions`  
**Headers:**
```http
Content-Type: application/json
Accept: application/json

  {
    "pay_mode":"credit",
    "date": "2025-10-30",
    "type": "purchase",
    "amount": 1500,
    "note": "Office supplies purchase"
  }
```
### Sale
```
{
    "pay_mode": "cash",
    "date": "2025-10-30",
    "type": "purchase",
    "amount": 1400,
    "note": "Sale product"
  }


{
  "status": 1,
  "message": "Transactions fetched successfully",
  "data":{
    "status": 1,
    "message": "Transaction added successfully",
    "data": {
        "debit_transaction": {
            "account_id": 3,
            "date": "2025-10-30",
            "type": "debit",
            "amount": 1500,
            "note": "Office supplies purchase",
            "updated_at": "2025-10-29T20:18:19.000000Z",
            "created_at": "2025-10-29T20:18:19.000000Z",
            "id": 11,
            "account": {
                "id": 3,
                "code": "AC-003",
                "name": "Purchase",
                "description": "Purchase account for recording all purchases",
                "balance": 1500,
                "created_at": null,
                "updated_at": "2025-10-29T20:18:19.000000Z",
                "transactions": [
                    {
                        "id": 11,
                        "date": "2025-10-30",
                        "account_id": 3,
                        "amount": "1500.00",
                        "type": "debit",
                        "note": "Office supplies purchase",
                        "created_at": "2025-10-29T20:18:19.000000Z",
                        "updated_at": "2025-10-29T20:18:19.000000Z"
                    }
                ]
            }
        },
        "credit_transaction": {
            "account_id": 6,
            "date": "2025-10-30",
            "type": "credit",
            "amount": 1500,
            "note": "Office supplies purchase",
            "updated_at": "2025-10-29T20:18:19.000000Z",
            "created_at": "2025-10-29T20:18:19.000000Z",
            "id": 12,
            "account": {
                "id": 6,
                "code": "AC-006",
                "name": "Accounts Payable",
                "description": "Money to be paid to suppliers",
                "balance": -1500,
                "created_at": null,
                "updated_at": "2025-10-29T20:18:19.000000Z",
                "transactions": [
                    {
                        "id": 1,
                        "date": "1994-04-02",
                        "account_id": 6,
                        "amount": "1071.00",
                        "type": "debit",
                        "note": "Delectus nisi facere exercitationem aperiam sed nulla.",
                        "created_at": "2025-10-29T18:37:24.000000Z",
                        "updated_at": "2025-10-29T18:37:24.000000Z"
                    },
                    {
                        "id": 12,
                        "date": "2025-10-30",
                        "account_id": 6,
                        "amount": "1500.00",
                        "type": "credit",
                        "note": "Office supplies purchase",
                        "created_at": "2025-10-29T20:18:19.000000Z",
                        "updated_at": "2025-10-29T20:18:19.000000Z"
                    }
                ]
            }
        }
    }
}

```
### Error Response:
```
Code: 400 / 404 / 500
{
  "status": 0,
  "message": "Transaction failed.",
  "error": "server error message"
}
```
