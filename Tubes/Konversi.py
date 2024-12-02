import json

def convert_to_jsonld(input_json):
    """
    Convert input JSON to JSON-LD format with specific schema mapping for Tokoh
    """
    output_jsonld_list = []

    for item in input_json:
        # Debugging: Print the current item to check the structure
        print(f"Processing item: {item}")
        
        try:
            # Get person details
            person_uri = item['person']['value']
            person_label = item['personLabel']['value']
            image = item.get('image', {}).get('value', '')
            birth_place = item['birthPlace']['value']
            birth_place_label = item['birthPlaceLabel']['value']
            latitude = float(item['latitude']['value'])
            longitude = float(item['longitude']['value'])
            occupation = item['occupationLabels']['value']
            birth_date = item['birthDate']['value']
            death_date = item.get('deathDate', {}).get('value', None)

            # Construct the JSON-LD output
            output_jsonld = {
                "https:///schema/Tokoh#Person": person_uri,
                "https:///schema/Tokoh#personLabel": person_label,
                "https:///schema/Tokoh#image": image,
                "https:///schema/Tokoh#birthPlace": birth_place,
                "https:///schema/Tokoh#birthPlaceLabel": birth_place_label,
                "https:///schema/Tokoh#latitude": latitude,
                "https:///schema/Tokoh#longitude": longitude,
                "https:///schema/Tokoh#occupation": occupation,
                "https:///schema/Tokoh#birthDate": birth_date,
                "https:///schema/Tokoh#deathDate": death_date
            }

            output_jsonld_list.append(output_jsonld)

        except KeyError as e:
            print(f"Missing key: {e} in item: {item}")
        
    return output_jsonld_list

def convert_query_json_to_jsonld(input_filename, output_filename):
    """
    Convert a specific query.json file to JSON-LD format
    
    Args:
    input_filename (str): Path to the input query.json file
    output_filename (str): Path to the output JSON-LD file
    """
    try:
        # Open and read the query.json file
        with open(input_filename, 'r', encoding='utf-8') as file:
            input_json = json.load(file)['results']['bindings']
            
            # Debugging: Print loaded data to check
            print(f"Loaded data from {input_filename}")
            
            # Convert the data to JSON-LD format
            converted_jsonld = convert_to_jsonld(input_json)
            
            # Save the converted data to the output file
            with open(output_filename, 'w', encoding='utf-8') as outfile:
                json.dump(converted_jsonld, outfile, indent=2, ensure_ascii=False)

            print(f"Converted data saved to {output_filename}")
            print(f"Total records processed: {len(converted_jsonld)}")

    except Exception as e:
        print(f"Error processing {input_filename}: {e}")

# Example usage
if __name__ == "__main__":
    input_filename = 'query.json'  # Path to the input query.json file
    output_filename = 'converted_query.jsonld'  # Path to the output JSON-LD file
    
    convert_query_json_to_jsonld(input_filename, output_filename)
