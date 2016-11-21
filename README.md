## Attempt to make the extension work with TYPO3 7.6

At least it seems to work without crashes ...

**Don't ask for support!**
 
I will not maintain this extension. Take it as it is. If something is not working for you, fork it and fix it.

## Breaking changes

### Template subparts

In grouped list view LIST_HEADER/LIST_FOOTER is no longer used. 

Instead use

- GROUPED_LIST_HEADER, GROUPED_LIST_FOOTER (used for one list group)
- GROUPED_LIST_ALL_HEADER, GROUPED_LIST_ALL_FOOTER (wrap around all lists)

Example:


    <!-- ###LIST_HEADER### -->
    <table id="phonebook">
    <!-- ###LIST_HEADER### -->
        
    <!-- ###GROUPED_LIST_ALL_HEADER### -->
    <table id="phonebook">
    <!-- ###GROUPED_LIST_ALL_HEADER### -->
    
    <!-- ###GROUPED_LIST_HEADER### -->
    <!-- ###GROUPED_LIST_HEADER### -->

## Note from the original author

This extension was built in 2006 and continuously extended until 2013. But as of now, **I do not maintain this extension anymore!**