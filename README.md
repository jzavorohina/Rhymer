# Rhymer class  
A PHP сlass for finding rhymes to words.

### Key features:
 - Finds rhymes to the word by the first syllable at the beginning of the word (for Russian words).
 - Finds rhymes to the word by the last syllable at the end of the word (for Russian words).


### Some examples:
```php
// Finds rhymes to the word by the first syllable at the beginning of the word
Rhymer::findByStart('Key word'); // return: array

// Finds rhymes to the word by the last syllable at the end of the word
Rhymer::findByEnd('Key word'); // return: array
```
