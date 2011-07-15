<?php

class Sabre_CardDAV_MockBackend extends Sabre_CardDAV_Backend_Abstract {

    public $addressBooks;
    public $cards;

    function __construct() {

        $this->addressBooks = array(
            array(
                'id' => 'foo',
                'uri' => 'book1',
                'principaluri' => 'principals/user1',
                '{DAV:}displayname' => 'd-name',
            ),
        );

        $this->cards = array(
            'foo' => array(
                'card1' => 'bar',
            ),
        );

    }


    function getAddressBooksForUser($principalUri) {

        $books = array();
        foreach($this->addressBooks as $book) {
            if ($book['principalUri'] === $principalUri) {
                $books[] = $book;
            }
        }

        return $books;

    }
    
    function updateAddressBook($addressBookId, array $mutations) {

        foreach($this->addressBooks as &$book) {
            if ($book['id'] !== $addressBookId)
                continue;

            foreach($mutations as $key=>$value) {
                $book[$key] = $value;
            }
            return true;
        }
        return false;

    }

    function createAddressBook($principalUri, $url, array $properties) {

        throw new Exception('Unsupported');

    }

    function deleteAddressBook($addressBookId) {

        foreach($this->addressBooks as $key=>$value) {
            if ($value['id'] === $addressBookId)
                unset($this->addressBooks[$key]);
        }
        unset($this->cards[$addressBookId]);

    }

    function getCards($addressBookId) {

        $cards = array();
        foreach($this->cards[$addressBookId] as $uri=>$data) {
            $cards[] = array(
                'uri' => $uri,
                'carddata' => $data,
            );
        }
        return $cards;

    }

    function getCard($addressBookId, $cardUri) {

        if (!isset($this->cards[$addressBookId][$cardUri])) {
            return false;
        }

        return array(
            'uri' => $cardUri,
            'carddata' => $this->cards[$addressBookId][$cardUri],
        );

    }

    function createCard($addressBookId, $cardUri, $cardData) {

        $this->cards[$addressBookId][$cardUri] = $cardData;

    }

    function updateCard($addressBookId, $cardUri, $cardData) {

        throw new Exception('Unsupported');

    }

    function deleteCard($addressBookId, $cardUri) {

        throw new Exception('Unsupported');

    }

}