# Laravel-Fints

This Laravel package is a wrapper for Php-Fints, which provides an Hbci connection to your bank in order to retrieve statements of your bank accounts. For more details about Php-Fints see its [docs](https://github.com/abiturma/php-fints).

## Installation

Available through composer:

`composer require abiturma/laravel-fints`

After installation publish the config file by calling

`php artisan vendor:publish --provider="Abiturma\LaravelFints\FintsServiceProvider"`.

you will find it under `config/laravel-fints.php`.

## Configuration

### Credential/Connection
You can *optionally* set your credentials in either `laravel-fints.php` (not recommended) or `.env`. It is not recommended to store your banking pin unencrypted in your `.env` file. 

### Pin encryption
To encrypt your pin set `encrypt_pin => true` in your config file. To encrypt your pin, run 
`php artisan fints:encryt_pin`, enter your pin when asked and copy the result to your `.env`.
Be aware that pin encryption provides only very limited safety since the encryption key is stored in the very same file. To have better safety it is recommended to aks the pin from the user on each request (see below). 

### Logging
By default logging is turned off. To enable logging, set `laravel-fints.logging.enabled` to `true`. In that case laravel's default logging channels will be used. To choose your own implementation of a logger provide the class name to `laravel-fints.logging.logger`. 

**Caution!**
Be aware that logging on debug level may expose your unencrypted pin in the log channel. 


## Usage
You can either use the class `Abiturma\LaravelFints\Fints` or the according facade. Both are used the same way, except that the first call on the facade is static.  

### Initialization
If not done using the config, you have to provide your connection details using the methods `host`, `port`, `bankCode`, `username` and `pin`. The methods can be chained in arbitrary order. If you have already specified some of those values in the config file you only need to fill the missing values.
 
```
use Fints; 

...

$fints = Fints::username('my-username')->pin('my-secret-pin'); 
```


### Retrieving a list of your (sepa) accounts

Once `Fints` is initialized, you can get a list of your accounts calling `$fints->getAccounts()`.
If the initalization is handled by the config, you would call the facade like so 
````
use Fints; 

...

$accounts = Fints::getAccounts()
````

 This method returns a collection of your bank accounts, i.e. an array of instances of `Abiturma\PhpFints\Models\Account`, which behave similar to laravel model classes. 
In particular the have magic getters and can be transformed to an array using `->toArray()`.  

### Retrieving the statement of a bank account

For a specific account, you can get a list of all transactions by calling `$fints->getStatementOfAccount($account)`. 
Optionally you can pass two `Carbon` objects to restrict the transactions to a specific date range:
`$fints->getStatementOfAccount($account, $from, $to)`. The result is a collection of objects of type `Abiturma\PhpFints\Models\Transaction`, which behave similar to laravel models. In particular you can retrieve a list of all attributes by calling `->toArray()` on them. 

Among others, the following attributes are stored on the transaction model: 

* `base_amount` the signed integer value of the transactions in cents (e.g. -120 means -1.20€)
* `amount` the signed float value of the transaction (e.g. -1.20)
* `remote_bank_code` the BIC of the remote account
* `remote_name` the name of the creditor/debitor
* `remote_account_number` the IBAN of the remote_account
* `date` the booking date of the transaction
* `value_date` the value (or valuta) date of the transaction
* `description`
* `end_to_end_reference`
* `prima_nota`

## SWIFT vs. Camt

By default, `Fints` tries to get statements in the Camt format, if possible. Sometimes it might occur, that this leads to thrown exceptions while parsing the response. In that case it might be useful to specify the response format explicitly. For that reason `Fints` exposes the methods `getSwiftStatementOfAccount` and `getCamtStatementOfAccount`, which have the same signature as `getStatementOfAccount`  


## Customizing the account model 
If you have your own account model it is not necessary to call  `->getAccounts()` first in order to get a statement of an account. Instead you can use your account model and make it implement `Abiturma\LaravelFints\IdentifiesBankAccount` by providing a method `getAccountAttributes()` which returns an array of the form 
```` 
[
    'iban' => 'Iban of the according account',
    'bic' => 'Bic of the according account',
    'account_number' => 'AccountNumber of the according account',
    'bank_code' => 'BankCode of the according account'
]
````
For example an implementation could look like this 
````
use Illuminate\Database\Eloquent\Model;
use Abiturma\LaravelFints\IdentifiesBankAccount;  

class MyAccount extends Model implements IdentifiesBankAccount { 

...

    public function getAccountAttributes() {
            
            return [
                 'iban' => $this->iban,
                 'bic' => $this->bic,
                 'account_number' => $this->account_number,
                 'bank_code' => $this->bank_code
            ]
    }

}
````

## Compatibility

The underlying hbci-library is work in progress and tested only with a small number of banks so far. [Here](https://github.com/abiturma/php-fints/blob/master/COMPATIBILITY.md) you can find a list of the tested banks. 

## Contribution

I'm looking forward to your feedback and suggestions. 




