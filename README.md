### 🗄️ Database Configuration

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

**Method:** `POST`  
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
Error Response:

Code: 400 / 404 / 500
{
  "status": 0,
  "message": "Failed to fetch transactions.",
  "error": "Account not found or server error"
}
```
